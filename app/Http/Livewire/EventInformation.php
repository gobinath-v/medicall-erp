<?php

namespace App\Http\Livewire;

use App\Models\Appointment;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\EventExhibitor;
use App\Models\EventVisitor;
use Livewire\Component;
use Illuminate\Http\Request;

class EventInformation extends Component
{
    public $event, $eventId, $exhibitorId, $visitorId;

    public $scheduledCount, $rescheduledCount, $confirmedCount, $lapsedCount, $cancelledCount, $completedCount;

    public $appointmentCount, $mappedExhibitors;

    public function confirmAppointment($appointmentId)
    {
        try {
            $appointment = Appointment::find($appointmentId);
            if ($appointment) {
                $appointment->status = 'confirmed';
                $appointment->save();
                $isUpdated = $appointment->wasChanged('status');
                if ($isUpdated) {
                    sendAppointmentStatusChangeNotification($appointment->visitor->mobile_number, 'exhibitor', [
                        'senderName' => $appointment->exhibitor->name ?? '',
                        'receiverName' => $appointment->visitor->name ?? '',
                        'status' => ucfirst($appointment->status),
                        'scheduledAt' => Carbon::parse($appointment->scheduled_at)->toDayDateTimeString(),
                    ]);
                    session()->flash('success', 'Appointment Confirmed with ' . $appointment->visitor->name);
                    return;
                }
            }
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return;
        }
    }

    public function mount(Request $request)
    {

        $this->eventId = $request->eventId;

        $isSalesPerson = isSalesPerson();

        $this->mappedExhibitors = mappedExhibitors($this->eventId);

        auth()->guard('exhibitor')->check() ?
            $this->exhibitorId = auth()->guard('exhibitor')->user()->id : '';
        auth()->guard('visitor')->check() ?
            $this->visitorId = auth()->guard('visitor')->user()->id : '';

        $this->event = Event::withCount('exhibitors', 'visitors')->where('id', $this->eventId)->first();

        if (auth()->guard('exhibitor')->check()) {
            $this->appointmentCount = Appointment::where('event_id', $this->eventId)->where('exhibitor_id', $this->exhibitorId)->count();
        } elseif (auth()->guard('visitor')->check()) {
            $this->appointmentCount = Appointment::where('event_id', $this->eventId)->where('visitor_id', $this->visitorId)->count();
        } else {
            $this->appointmentCount = Appointment::when($isSalesPerson, function ($query) {
                $query->whereIn('exhibitor_id', $this->mappedExhibitors);
            })->where('event_id', $this->eventId)->count();
        }
    }

    public function render()
    {
        $pendingAppointments = Appointment::where('exhibitor_id', $this->exhibitorId)
            ->where('event_id', $this->eventId)
            ->whereIn('status', ['scheduled', 'rescheduled'])
            ->select('id', 'visitor_id', 'scheduled_at', 'notes')
            ->orderBy('id', 'desc')->paginate(10);

        if (auth()->guard('visitor')->check()) {
            $this->scheduledCount = Appointment::where('event_id', $this->eventId)->where('visitor_id', $this->visitorId)->where('status', 'scheduled')->count();
            $this->rescheduledCount = Appointment::where('event_id', $this->eventId)->where('visitor_id', $this->visitorId)->where('status', 'rescheduled')->count();
            $this->confirmedCount = Appointment::where('event_id', $this->eventId)->where('visitor_id', $this->visitorId)->where('status', 'confirmed')->count();
            $this->lapsedCount = Appointment::where('event_id', $this->eventId)->where('visitor_id', $this->visitorId)->where('status', 'no-show')->count();
            $this->cancelledCount = Appointment::where('event_id', $this->eventId)->where('visitor_id', $this->visitorId)->where('status', 'cancelled')->count();
            $this->completedCount = Appointment::where('event_id', $this->eventId)->where('visitor_id', $this->visitorId)->where('status', 'completed')->count();
        }
        if (auth()->guard('exhibitor')->check()) {
            $this->scheduledCount = Appointment::where('event_id', $this->eventId)->where('exhibitor_id', $this->exhibitorId)->where('status', 'scheduled')->count();
            $this->rescheduledCount = Appointment::where('event_id', $this->eventId)->where('exhibitor_id', $this->exhibitorId)->where('status', 'rescheduled')->count();
            $this->confirmedCount = Appointment::where('event_id', $this->eventId)->where('exhibitor_id', $this->exhibitorId)->where('status', 'confirmed')->count();
            $this->lapsedCount = Appointment::where('event_id', $this->eventId)->where('exhibitor_id', $this->exhibitorId)->where('status', 'no-show')->count();
            $this->cancelledCount = Appointment::where('event_id', $this->eventId)->where('exhibitor_id', $this->exhibitorId)->where('status', 'cancelled')->count();
            $this->completedCount = Appointment::where('event_id', $this->eventId)->where('exhibitor_id', $this->exhibitorId)->where('status', 'completed')->count();
        }

        return view(
            'livewire.event-information',
            [
                'pendingAppointments' => $pendingAppointments,
                'scheduledCount' => $this->scheduledCount,
                'rescheduledCount' => $this->rescheduledCount,
                'confirmedCount' => $this->confirmedCount,
                'lapsedCount' => $this->lapsedCount,
                'canceledCount' => $this->cancelledCount,
            ]
        )->layout('layouts.admin');
    }
}
