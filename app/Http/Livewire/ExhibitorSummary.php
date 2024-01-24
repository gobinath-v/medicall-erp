<?php

namespace App\Http\Livewire;

use App\Models\Event;
use App\Models\EventExhibitor;
use App\Models\Exhibitor;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use Livewire\Attributes\Url;
use App\Exports\ExhibitorsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExhibitorSummary extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    #[Url]
    public $search;
    public $is_checked = false;
    public $exhibitor_id = null;
    public $stall_space;
    public $square_space;
    public $stall_no;
    public $eventId;
    #[Url( as: 'product')]
    public $productSearch;
    public $products = [];
    public $showFilter = false;
    public $sortName = 'id';
    public $sortDirection = 'desc';
    public $sortedTable;
    public $events = [];
    public $event_id;
    public $selectAll = false;
    public $showToggle = false;
    public $selectedExhibitors = [];
    protected $rules = [
        'stall_space' => 'required',
        'square_space' => 'required',
        'stall_no' => 'required',
    ];
    protected $messages = [
        'stall_space.required' => 'Stall Space is required',
        'square_space.required' => 'Square Space is required',
        'stall_no.required' => 'Stall No is required',
    ];
    public function mount(Request $request)
    {
        $this->eventId = request()->eventId;
        $this->productSearch = request()->product;
        if ($this->search !== null || $this->productSearch !== null) {
            $this->showFilter = true;
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $exhibitor = $this->getFilteredExhibitors()->get();
            $this->selectedExhibitors = $exhibitor->pluck('id');
        } else {
            $this->selectedExhibitors = [];
        }
    }

    public function toggleEvents()
    {
        $this->showToggle = !$this->showToggle;
    }
    public function selectedExhibitorsId()
    {
        $isCreated = null;
        if (count($this->selectedExhibitors) > 0) {
            $exhibitorRecords = Exhibitor::whereIn('id', $this->selectedExhibitors)->get();
            foreach ($exhibitorRecords as $exhibitorRecord) {
                $isExists = $exhibitorRecord->eventExhibitors()->where('event_id', $this->event_id)->exists();
                if (!$isExists) {
                    $isCreated = $exhibitorRecord->eventExhibitors()->create([
                        'event_id' => $this->event_id,
                    ]);
                }
            }
            if ($isCreated) {
                redirect()->route('exhibitor.summary');
                session()->flash('success', 'Exhibitors added successfully.');
            }
        } else {
            session()->flash('info', 'Please select atleast one exhibitor.');
        }
    }

    public function getExhibitorId($id)
    {
        $this->exhibitor_id = $id;
        $exhibitor = Exhibitor::find($this->exhibitor_id);
        $eventExhibitorInfo = $exhibitor->eventExhibitors->where('event_id', $this->eventId)->first();
        $this->stall_no = $eventExhibitorInfo->stall_no ?? '';
        $this->stall_space = $eventExhibitorInfo->_meta['stall_space'] ?? '';
        $this->square_space = $eventExhibitorInfo->_meta['square_space'] ?? '';
    }
    public function updateStallDetail()
    {

        $this->validate();
        $isStallNoExists = EventExhibitor::where('event_id', $this->eventId)
            ->where('stall_no', $this->stall_no)
            ->where('exhibitor_id', '!=', $this->exhibitor_id)
            ->first();
        if ($isStallNoExists) {
            $this->addError('stall_no', 'Stall No already exists');
            return;
        }
        if ($this->exhibitor_id) {
            $exhibitor = Exhibitor::find($this->exhibitor_id);
            $exhibitor->eventExhibitors()->where('event_id', $this->eventId)->update([
                'stall_no' => $this->stall_no,
                '_meta' => [
                    'stall_space' => $this->stall_space,
                    'square_space' => $this->square_space,
                ],
            ]);
        }
        $this->dispatch('closeModal');
        session()->flash('success', 'Stall detail updated successfully.');
    }

    public function sortBy($sortTable, $sortName, $sortDirection)
    {
        $this->sortedTable = $sortTable;
        $this->sortName = $sortName;
        $this->sortDirection = $sortDirection;
    }
    public function clearError()
    {
        $this->resetErrorBag();
        $this->dispatch('closeModal');
    }
    private function getFilteredExhibitors()
    {

        $query = Exhibitor::query();

        if ($this->eventId) {
            $query->whereHas('eventExhibitors', function ($query) {
                $query->where('event_id', $this->eventId)
                    ->when(isSalesPerson(), function ($query) {
                        $query->whereIn('exhibitor_id', mappedExhibitors($this->eventId));
                    });
            });
        }

        $query->where(function ($query) {
            // Product Search
            if (!empty($this->productSearch)) {
                if ($this->eventId) {
                    $query->whereHas('eventExhibitors', function ($query) {
                        $query->whereJsonContains('products', $this->productSearch);
                    });
                } else {
                    $query->whereHas('exhibitorProducts', function ($query) {
                        $query->where('product_id', $this->productSearch);
                    });
                }
            }

            // General Search
            if (!empty($this->search)) {
                $trimmedSearch = trim($this->search);
                $query->where(function ($query) use ($trimmedSearch) {
                    $query->where('exhibitors.name', 'LIKE', '%' . $trimmedSearch . '%')
                        ->orWhere('exhibitors.mobile_number', 'LIKE', '%' . $trimmedSearch . '%')
                        ->orWhere('exhibitors.email', 'LIKE', '%' . $trimmedSearch . '%')
                        ->orWhereHas('exhibitorContact', function ($query) use ($trimmedSearch) {
                            $query->where('exhibitor_contacts.contact_number', 'LIKE', '%' . $trimmedSearch . '%')
                                ->orWhere('exhibitor_contacts.name', 'LIKE', '%' . $trimmedSearch . '%');
                        })
                        ->orWhereHas('address', function ($query) use ($trimmedSearch) {
                            $query->where('addresses.city', 'LIKE', '%' . $trimmedSearch . '%')
                                ->where('addresses.addressable_type', 'App\Models\Exhibitor');
                        });
                });
            }
        });
        if ($this->sortedTable === 'contact_person') {
            $query->join('exhibitor_contacts', 'exhibitor_contacts.exhibitor_id', '=', 'exhibitors.id')
                ->select('exhibitors.*')
                ->orderBy('exhibitor_contacts.' . $this->sortName, $this->sortDirection);
        }
        if ($this->sortedTable === 'address') {
            $query->join('addresses', function ($join) {
                $join->on('addresses.addressable_id', '=', 'exhibitors.id')
                    ->where('addresses.addressable_type', '=', 'App\Models\Exhibitor');
            })
                ->select('exhibitors.*')
                ->orderBy('addresses.' . $this->sortName, $this->sortDirection);
        }
        if ($this->sortedTable === 'appointments') {
            $query->withCount(['appointments' => function ($query) {
                $query->when($this->eventId, function ($query) {
                    $query->where('event_id', $this->eventId);
                });
            }])
                ->orderBy($this->sortName , $this->sortDirection);
        }
        return $query->orderBy($this->sortName, $this->sortDirection);
    }
    public function render()
    {
        $exhibitors = $this->getFilteredExhibitors()->paginate(10);
        $this->products = Product::pluck('name', 'id');
        $this->events = Event::where('start_date', '>=', now()->format('Y-m-d'))
            ->orWhere('end_date', '>', now()->format('Y-m-d'))
            ->pluck('title', 'id');
        return view('livewire.exhibitor-summary', compact('exhibitors'))->layout('layouts.admin');
    }


    public function toggleBtn()
    {
        $this->showFilter = !$this->showFilter;
    }
    public function exportToExcel()
    {
        $exhibitorsData = $this->getFilteredExhibitors()->get();
        if (count($exhibitorsData) > 0) {
            return (new ExhibitorsExport($this->eventId, $exhibitorsData))->download('exhibitors.xlsx');
        }
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'search' || $propertyName === 'productSearch') {
            $this->resetPage();
        }
    }
}
