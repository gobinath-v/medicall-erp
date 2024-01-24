<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Exhibitor;
use Illuminate\Http\Request;
use Livewire\Attributes\Url;
use App\Models\EventExhibitor;

class MappingToExhibitor extends Component
{
    public $perPage = 10;

    public $eventId;

    public $userName, $userId;

    public $toggleContent = false;

    public $exhibitors = [];

    public $exhibitorId = [];

    #[Url(as: 'search')]
    public $search;

    public $oldExhibitor = [];

    public $orderBy = 'asc', $orderByName = 'name';

    public function resetFilter()
    {
        $this->search = null;
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

    public function mount(Request $request)
    {
        $this->eventId = $request->eventId;
        $this->exhibitors = Exhibitor::whereHas('eventExhibitors', function ($query) {
            $query->where('event_id', $this->eventId);
        })->where('deleted_by', null)->select('id', 'name')->get();
    }

    public function render()
    {
        $users = User::when(isset($this->search), function ($query) {
            $query->where(function ($subQuery) {
                $subQuery->whereHas('eventExhibitors', function ($getExhibitor) {
                    $getExhibitor->whereHas('exhibitor', function ($getName) {
                        $getName->where('name', 'like', '%' . trim($this->search) . '%');
                    });
                });
            })->orWhere('name', 'like', '%' . trim($this->search) . '%');
        })->where('type', 'sales_person')
            ->where('is_active', 1)
            ->when($this->orderByName == 'type', function ($sort) {
                $sort->orderBy($this->orderByName, $this->orderBy);
            })
            ->paginate($this->perPage,pageName:'p');
        return view('livewire.mapping-to-exhibitor', compact('users'))->layout('layouts.admin');
    }

    public function getUserId($id)
    {
        $this->userId = $id;
        $user = User::find($this->userId);
        $this->userName = $user->name;
        $this->exhibitorId = EventExhibitor::where('sales_person_id', $this->userId)->pluck('exhibitor_id')->flatten()->toArray();
        $this->oldExhibitor = $this->exhibitorId;
        $this->dispatch('setValueInTomSelect', id: $this->exhibitorId);
    }

    public function mapExhibitor()
    {

        $unlinkExhibitorIds = array_diff($this->oldExhibitor, $this->exhibitorId);
        $newExhibitorIds = array_diff($this->exhibitorId, $this->oldExhibitor);


        if (!empty($unlinkExhibitorIds) && count($unlinkExhibitorIds) > 0) {
            $unlinkExhibitors = EventExhibitor::whereIn('exhibitor_id', $unlinkExhibitorIds)
                ->where('event_id', $this->eventId)
                ->update([
                    'sales_person_id' => null,
                ]);
            if ($unlinkExhibitors > 0) {
                $this->closeModal();
                session()->flash('success', 'Exhibitors successfully unmapped for ' . $this->userName);
                // return;
            }
        }

        if (!empty($newExhibitorIds) && count($newExhibitorIds) > 0) {
            $addExhibitors = EventExhibitor::where('sales_person_id', null)
                ->whereIn('exhibitor_id', $newExhibitorIds)
                ->where('event_id', $this->eventId)->get();

            if (!empty($addExhibitors) && count($addExhibitors) > 0) {
                foreach ($addExhibitors as $addExhibitor) {
                    $addExhibitor->sales_person_id = $this->userId;
                    $addExhibitor->save();
                }
                $isUpdated = $addExhibitor->wasChanged('sales_person_id');
                if ($isUpdated) {
                    $this->closeModal();
                    session()->flash('success', 'Exhibitors successfully mapped for ' . $this->userName);
                    return;
                }
                $this->closeModal();
                session()->flash('info', 'Do some Modification ');
                return;
            }
            $this->closeModal();
            session()->flash('info', 'This Exhibitor already mapped to another sales person ');
            return;
        }
    }

    public function closeModal()
    {
        $this->reset([
            'exhibitorId',
        ]);
        $this->dispatch('closeModal');
    }

    public function changePageValue($perPageValue)
    {
        $this->perPage = $perPageValue;
        // $this->resetPage(pageName: 'p');
    }

    public function toggleBtn()
    {
        $this->toggleContent = !$this->toggleContent;
    }
}
