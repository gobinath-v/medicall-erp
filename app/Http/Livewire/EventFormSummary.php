<?php

namespace App\Http\Livewire;

use App\Models\Event;
use App\Models\Visitor;
use Livewire\Component;
use App\Models\Exhibitor;
use App\Models\Appointment;
use App\Models\EventVisitor;
use App\Models\EventExhibitor;

class EventFormSummary extends Component
{
    public $perPage = 4;

    public $currentEventId;

    public $eventId;

    public $mappedExhibitors;

    public function registerVisitor($eventId)
    {
        $visitorInfo = Visitor::find(getAuthData()->id);
        $visitorInfo->eventVisitors()->create([
            'event_id' => $eventId,
        ]);
        session()->flash('success', 'Registered Successfully.');
    }

    public function registerExhibitor($eventId)
    {
        $exhibitorInfo = Exhibitor::find(getAuthData()->id);
        $exhibitorInfo->eventExhibitors()->create([
            'event_id' => $eventId,
        ]);
        session()->flash('success', 'Registered Successfully.');
    }

    public function mount()
    {
        $this->eventId = request()->eventId ?? '';
    }

    public function render()
    {
        $isSalesPerson = isSalesPerson();
        if (isset($this->eventId) && !empty($this->eventId)) {
            $currentEvent = Event::where('id', $this->eventId)
                ->select('title', 'start_date', 'end_date', 'id', '_meta')
                ->orderBy('start_date', 'asc')
                ->first();
        } else {
            $currentEvent = getCurrentEvent();
            // Event::where('start_date', '>=', now()->format('Y-m-d'))
            //     ->orWhere('end_date', '>=', now()->format('Y-m-d'))
            //     ->select('title', 'start_date', 'end_date', 'id', '_meta')
            //     ->orderBy('start_date', 'asc')
            //     ->paginate(1);
        }

        $this->currentEventId = $currentEvent->id ?? null;

        $upcomingEventThumbnails = Event::whereNotIn('id', [$this->currentEventId,getCurrentEvent()->id])
            ->where('start_date', '>=', now()->format('Y-m-d'))
            ->select('id', 'title', '_meta')
            ->orderBy('start_date', 'asc')
            ->paginate($this->perPage);

        // dd($currentEvent->id, $currentEvent, $this->currentEventId, $upcomingEventThumbnails);
        $exibitorRegisteredEvents = [];
        $visitorRegisteredEvents = [];
        // foreach ($currentEvent as $event) {
        $isExhibitorRegistered = EventExhibitor::where('event_id', $currentEvent->id)
            ->where('exhibitor_id', getAuthData()->id)
            ->exists();

        $isVisitorRegistered = EventVisitor::where('event_id', $currentEvent->id)
            ->where('visitor_id', getAuthData()->id)
            ->exists();
        $visitorRegisteredEvents[$currentEvent->id] = $isVisitorRegistered;
        $exibitorRegisteredEvents[$currentEvent->id] = $isExhibitorRegistered;
        // }

        if ($isSalesPerson) {
            $this->mappedExhibitors = mappedExhibitors($this->currentEventId);
        }

        $exhibitors = EventExhibitor::when($isSalesPerson, function ($query) {
            $query->where('sales_person_id', getAuthData()->id);
        })->where('event_id', $this->currentEventId)->orderBy('id', 'desc')->take(5)->get();

        $appointments = Appointment::when($isSalesPerson, function ($query) {
            $query->whereIn('exhibitor_id', $this->mappedExhibitors);
        })->where('event_id', $this->currentEventId)->select('event_id', 'visitor_id', 'exhibitor_id', 'scheduled_at')->orderBy('id', 'desc')->take(5)->get();

        $scheduledAppointmentCount = Appointment::when($isSalesPerson, function ($query) {
            $query->whereIn('exhibitor_id', $this->mappedExhibitors);
        })->where('event_id', $this->currentEventId)->where('status', 'scheduled')->orderBy('id', 'desc')->count();

        $rescheduledAppointmentCount = Appointment::when($isSalesPerson, function ($query) {
            $query->whereIn('exhibitor_id', $this->mappedExhibitors);
        })->where('event_id', $this->currentEventId)->where('status', 'rescheduled')->orderBy('id', 'desc')->count();

        $confirmedAppointmentCount = Appointment::when($isSalesPerson, function ($query) {
            $query->whereIn('exhibitor_id', $this->mappedExhibitors);
        })->where('event_id', $this->currentEventId)->where('status', 'confirmed')->orderBy('id', 'desc')->count();

        $lapsedAppointmentCount = Appointment::when($isSalesPerson, function ($query) {
            $query->whereIn('exhibitor_id', $this->mappedExhibitors);
        })->where('event_id', $this->currentEventId)->where('status', 'no-show')->orderBy('id', 'desc')->count();

        $cancelledAppointmentCount = Appointment::when($isSalesPerson, function ($query) {
            $query->whereIn('exhibitor_id', $this->mappedExhibitors);
        })->where('event_id', $this->currentEventId)->where('status', 'cancelled')->orderBy('id', 'desc')->count();

        $completedAppointmentCount = Appointment::when($isSalesPerson, function ($query) {
            $query->whereIn('exhibitor_id', $this->mappedExhibitors);
        })->where('event_id', $this->currentEventId)->where('status', 'completed')->orderBy('id', 'desc')->count();

        $exhibitorCount = EventExhibitor::when($isSalesPerson, function ($query) {
            $query->where('sales_person_id', getAuthData()->id);
        })->where('event_id', $this->currentEventId)->count();

        $visitorCount = EventVisitor::where('event_id', $this->currentEventId)->count();

        $appointmentCount = Appointment::when($isSalesPerson, function ($query) {
            $query->whereIn('exhibitor_id', $this->mappedExhibitors);
        })->where('event_id', $this->currentEventId)->where('cancelled_by', null)->count();


        return view('livewire.event-form-summary', [
            'currentEvent' => $currentEvent,
            'upcomingEventThumbnails' => $upcomingEventThumbnails,
            'exibitorRegisteredEvents' => $exibitorRegisteredEvents,
            'visitorRegisteredEvents' => $visitorRegisteredEvents,
            'exhibitors' => $exhibitors,
            'appointments' => $appointments,
            'scheduledAppointmentCount' => $scheduledAppointmentCount,
            'rescheduledAppointmentCount' => $rescheduledAppointmentCount,
            'confirmedAppointmentCount' => $confirmedAppointmentCount,
            'lapsedAppointmentCount' => $lapsedAppointmentCount,
            'canceledAppointmentCount' => $cancelledAppointmentCount,
            'exhibitorCount' => $exhibitorCount,
            'appointmentCount' => $appointmentCount,
            'visitorCount' => $visitorCount,
            'completedAppointmentCount' => $completedAppointmentCount,

        ])->layout('layouts.admin');
    }
}
