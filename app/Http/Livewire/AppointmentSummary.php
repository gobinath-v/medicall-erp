<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Appointment;
use App\Models\EventVisitor;
use Illuminate\Http\Request;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use App\Exports\AppointmentExport;
use Maatwebsite\Excel\Facades\Excel;

class AppointmentSummary extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Url(as: 'pp')]
    public $perPage = 10;

    public $eventId, $appointmentStatus, $appointmentId;

    #[Url(as: 's')]
    public $search;
    #[Url(as: 'df')]
    public $dateFilter;

    public $orderBy = 'asc';

    public $orderByDate = 'asc';

    public $orderByName = 'scheduled_at';

    public $toggleContent = false;

    public $sortedFeedbacks = [];

    public $isSelectAll = false, $fetchAllRecords = false;

    public $selectedRecordIds = [];

    public $selectRecordsCount;

    public $totalCount;

    public function mount(Request $request)
    {
        $this->eventId = $request->eventId;
        $this->appointmentStatus = $request->appointmentStatus;
    }
    public function resetField()
    {
        $this->reset([
            'search',
        ]);
    }
    public function resetDate()
    {
        $this->reset([
            'dateFilter'
        ]);
    }

    public function toggleBtn()
    {
        $this->toggleContent = !$this->toggleContent;
    }
    public function changePageValue($perPageValue)
    {
        $this->perPage = $perPageValue;
        $this->resetPage();
    }
    public function orderByAsc($columnName)
    {
        $this->orderBy = 'asc';
        $this->orderByName = $columnName;
    }
    public function orderByDesc($columnName)
    {
        $this->orderBy = 'desc';
        $this->orderByName = $columnName;
    }
    public function exhibitorAppointmentId($appointmentId)
    {
        $this->appointmentId = $appointmentId;
        $this->getFeedback();
    }
    public function getFeedback()
    {
        $viewFeedback = Appointment::find($this->appointmentId);

        $visitorFeedback = $viewFeedback->_meta['visitor_feedback'] ?? null;
        $exhibitorFeedback = $viewFeedback->_meta['exhibitor_feedback'] ?? null;

        $feedbacks = collect([
            [
                'name' => $viewFeedback->visitor->name ?? '',
                'logo' => $viewFeedback->visitor->_meta ?? '',
                'message' => $visitorFeedback['message'] ?? '',
                'timestamp' => $visitorFeedback['timestamp'] ?? null,
                'type' => 'visitor',
            ],
            [
                'name' => $viewFeedback->exhibitor->name ?? '',
                'logo' => $viewFeedback->exhibitor->logo ?? '',
                'message' => $exhibitorFeedback['message'] ?? '',
                'timestamp' => $exhibitorFeedback['timestamp'] ?? null,
                'type' => 'exhibitor',
            ],
        ]);

        $this->sortedFeedbacks = $feedbacks->sort(function ($a, $b) {
            $timestampA = $a['timestamp'] ?? null;
            $timestampB = $b['timestamp'] ?? null;

            if ($timestampA === null) {
                return 1;
            }

            if ($timestampB === null) {
                return -1;
            }

            return $timestampA <=> $timestampB;
        });


        $this->sortedFeedbacks->values()->all();
    }
    public function exportData()
    {

        // if ($this->fetchAllRecords) {
        $allAppointments = $this->getAppointmentRecords()->get();
        $this->selectedRecordIds  = $allAppointments->pluck('id');
        $this->selectRecordsCount = count($this->selectedRecordIds);
        // }
        if ($this->selectRecordsCount > 0) {
            return (new AppointmentExport($this->selectedRecordIds, $this->orderByName, $this->orderBy))->download('appointments.xlsx');
        } else {
            return session()->flash('info', 'No Records To Export');
        }
    }
    // public function selectedRows($id)
    // {
    //     $this->selectedRecordIds[] = $id;
    //     $this->selectRecordsCount = count(array_unique($this->selectedRecordIds));
    // }
    public function getAllRecords()
    {
        $this->fetchAllRecords = true;
    }
    public function clearSelection()
    {
        return redirect()->route('appointment.summary', ['p' => $this->paginators['p']]);
        // $this->isSelectAll = false;
        // $this->fetchAllRecords = false;
        // $this->selectedRecordIds = [];
        // $this->selectRecordsCount = count($this->selectedRecordIds);
        // $this->dispatch('clearCheckboxSelection');
    }
    private function getAppointmentRecords()
    {

        $appointments = Appointment::when(isset($this->eventId), function ($query) {
            $query->where('event_id', $this->eventId)
                ->when(isSalesPerson(), function ($query) {
                    $query->whereIn('exhibitor_id', mappedExhibitors($this->eventId));
                });
        })
            ->when($this->search !== null, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('visitor', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    })
                        ->orWhereHas('exhibitor', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when(($this->dateFilter !== null), function ($query) {
                $query->whereDate('scheduled_at', $this->dateFilter);
            })
            ->when(isset($this->appointmentStatus), function ($status) {
                $status->where('status', $this->appointmentStatus);
            })
            ->when($this->orderByName == 'visitor', function ($sort) {
                $sort->join('visitors', 'appointments.visitor_id', '=', 'visitors.id')
                    ->select('appointments.*')->orderBy('visitors.name', $this->orderBy);
            })
            ->when($this->orderByName == 'designation', function ($sort) {
                $sort->join('visitors', 'appointments.visitor_id', '=', 'visitors.id')
                    ->select('appointments.*')->orderBy('visitors.designation', $this->orderBy);
            })
            ->when($this->orderByName == 'exhibitor', function ($sort) {
                $sort->join('exhibitors', 'appointments.exhibitor_id', '=', 'exhibitors.id')
                    ->select('appointments.*')->orderBy('exhibitors.name', $this->orderBy);
            })
            ->when($this->orderByName == 'scheduled_at', function ($sort) {
                $sort->orderBy($this->orderByName, $this->orderBy);
            })
            ->when($this->orderByName == 'status', function ($sort) {
                $sort->orderBy($this->orderByName, $this->orderBy);
            });
        return $appointments;
    }
    public function render()
    {

        $appointments = $this->getAppointmentRecords()->paginate(
            $this->perPage,
            pageName: 'p'
        );

        // if ($this->isSelectAll) {
        //     $this->selectedRecordIds =  $appointments->pluck('id')->toArray();
        //     $this->selectRecordsCount = count($this->selectedRecordIds);
        //     $this->totalCount = $appointments->total();
        // }

        // if ($this->fetchAllRecords) {
        //     $allAppointments = $this->getAppointmentRecords()->get();
        //     $this->selectedRecordIds  = $allAppointments->pluck('id');
        //     $this->selectRecordsCount = count($this->selectedRecordIds);
        // }

        if ($this->search !== '' || $this->dateFilter !== '') {
            $this->resetPage();
        }
        return view(
            'livewire.appointment-summary',
            [
                'appointments' => $appointments,
                'selectRecordsCount' =>  $this->selectRecordsCount,
                'totalCount' => $this->totalCount,
                'feedbacks' => $this->sortedFeedbacks
            ]
        )->layout('layouts.admin');
    }
    public function updated($propertyName)
    {
        if ($propertyName === 'search' || $propertyName === 'dateFilter') {
            $this->resetPage();
        }
    }
}
