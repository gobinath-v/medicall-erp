<?php

namespace App\Http\Livewire;

use App\Exports\VisitorsExport;
use App\Models\Event;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class VisitorSummary extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $eventId;
    #[Url]
    public $search = '';
    public $sortBy = 'id';
    public $sortDirection = 'desc';
    public $events = [];
    public $event_id;
    public $selectAll = false;
    public $showToggle = false;
    public $selectedVisitors = [];
    protected $queryString = ['search'=> ['except' => '']];
    public $visitorId;

    protected $listeners = [
        'message' => 'alertStatus'
    ];

    public function alertStatus($status, $message)
    {
        session()->flash($status, $message);
    }
    public $exporting = false;

    public function mount(Request $request)
    {
        $this->eventId = $request->eventId;

        // dd($this->eventId);
    }
    public function sortColumn($field, $order = 'asc')
    {
        $this->sortDirection = $order;
        $this->sortBy = $field;
    }

    public function applySorting($query)
    {
        if ($this->sortBy && in_array($this->sortBy, ['id', 'name', 'mobile_number', 'email', 'organization'])) {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }
    }
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedVisitors = Visitor::pluck('id');
        } else {
            $this->selectedVisitors = [];
        }

    }

    public function toggleEvents()
    {
        $this->showToggle = !$this->showToggle;
    }
    public function selectedVisitorsId()
    {
        $isCreated = null;
        if (count($this->selectedVisitors) > 0) {
            $visitorRecords = Visitor::whereIn('id', $this->selectedVisitors)->get();
            foreach ($visitorRecords as $visitorRecord) {
                $isExists = $visitorRecord->eventVisitors()->where('event_id', $this->event_id)->exists();
                if (!$isExists) {
                    $isCreated = $visitorRecord->eventVisitors()->create([
                        'event_id' => $this->event_id,
                    ]);
                }
            }
            if ($isCreated) {
                redirect()->route('visitors.summary');
                session()->flash('success', 'Visitors added successfully.');
            }
        } else {
            session()->flash('info', 'Please select atleast one visitor.');
        }
    }

    public function getVisitor($id)
    {
        $this->visitorId = $id;
        $this->dispatch('selectedVisitor', [$this->visitorId]);
    }

    private function getFilteredVisitors()
    {
        return Visitor::query()
            ->when($this->eventId, function ($query) {
                $query->whereHas('eventVisitors', function ($query) {
                    $query->where('event_id', $this->eventId);
                });
            })
            ->when(trim($this->search), function ($query) {
                $query->where(function ($query) {
                    $trimmedSearch = trim($this->search);

                    $query->where('name', 'like', '%' . $trimmedSearch . '%')
                        ->orWhere('mobile_number', 'like', '%' . $trimmedSearch . '%')
                        ->orWhere('email', 'like', '%' . $trimmedSearch . '%')
                        ->orWhere('organization', 'like', '%' . $trimmedSearch . '%');
                });
            })
            ->when($this->sortBy, function ($query) {
                if ($this->sortBy === 'appointments_count') {
                    $query->withCount(['appointments' => function ($query) {
                        $query->when($this->eventId, function ($query) {
                            $query->where('event_id', $this->eventId);
                        });
                    }])
                        ->orderBy($this->sortBy, $this->sortDirection);
                } else {
                    $this->applySorting($query);
                }
            });

    }

    public function render()
    {
        $visitorsQuery = $this->getFilteredVisitors();
        $visitors = $visitorsQuery->paginate(10);

        $this->events = Event::where('start_date', '>=', now()->format('Y-m-d'))
            ->orWhere('end_date', '>', now()->format('Y-m-d'))
            ->pluck('title', 'id');

        return view('livewire.visitor-summary', [
            'visitors' => $visitors,
        ])->layout('layouts.admin');
    }

    public function exportToExcel()
    {
        $visitorsData = $this->getFilteredVisitors()->get();

        if (count($visitorsData) > 0) {
            $export = new VisitorsExport($this->eventId, $visitorsData);
            return $export->download('visitors.xlsx');
        }
    }
    public function updatedSearch()
    {
        $this->resetPage();
    }

}
