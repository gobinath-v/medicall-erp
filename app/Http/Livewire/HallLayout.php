<?php

namespace App\Http\Livewire;

use App\Models\Event;
use Livewire\Component;
use Illuminate\Http\Request;

class HallLayout extends Component
{
    public $eventId;



    public function mount(Request $request)
    {
        $this->eventId = $request->eventId;
    }

    public function render()
    {
        $event = Event::where('id', $this->eventId)->select('title', '_meta')->first();

        if(isset($event->_meta['layout']) && empty($event->_meta['layout'])){
         session()->flash('info',' Hall Layout To Be Upload Soon');

        }
        return view('livewire.hall-layout', [
            'event' => $event,
        ])->layout('layouts.admin');
    }
}
