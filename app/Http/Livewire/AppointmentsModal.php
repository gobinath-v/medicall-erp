<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use App\Models\Event;
use Livewire\Component;
use App\Models\Exhibitor;
use App\Models\Appointment;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AppointmentsModal extends Component
{
    public $exhibitor;
    public $visitorId, $exhibitorId;
    public $eventId;
    public $scheduledAt;
    public $notes;
    public $dateList = [];
    public $time;
    public $appointmentId = null;
    public $appointmentData;
    public $selectedExhibitorId;
    public $visitor;
    // public $status='rescheduled';

    protected $listeners = [
        'exhibitorSelected' => 'getExhibitor',
        'selectedExhibitor' => 'getExhibitor',
        'getAppointmentIdListener' => 'getAppointmentId',
        'selectedVisitor',
    ];

    protected $rules = [
        'scheduledAt' => 'required|date_format:Y-m-d|after_or_equal:now',
        'notes' => 'nullable|string',
        'time' => 'required|date_format:H:i|after_or_equal:10:00|before_or_equal:18:00',
    ];

    protected $messages = [
        'scheduledAt.required' => 'The scheduled at field is required.',
        'scheduledAt.date_format' => 'Invalid date format. Please use YYYY-MM-DDTHH:mm format.',
        'scheduledAt.after_or_equal' => 'Please choose a future or current date and time.',
        'time.required_if' => 'Please select time',
        'time.after_or_equal' => 'Invalid Time. after(Appoinment Hours: 10 am to 6pm)',
        'time.before_or_equal' => 'Invalid Time.(Appoinment Hours: 10 am to 6pm)',
    ];

    public function getAppointmentId($appoinmentId)
    {
        $this->appointmentId = $appoinmentId;
        if (isset($this->appointmentId)) {
            // $this->selectedEvent  = Event::find($this->eventId);
            $this->eventId = $this->eventId;
            $this->appointmentData = Appointment::find($this->appointmentId);
            $this->scheduledAt = $this->appointmentData->scheduled_at->format('Y-m-d');
            $this->time = $this->appointmentData->scheduled_at->format('H:i');
            $this->notes = $this->appointmentData->notes;
        }
    }

    public function getExhibitor($values)
    {
        [$selectedExhibitorId, $eventId] = $values;
        $exhibitorData = Exhibitor::where('id', $selectedExhibitorId)->first();
        // $selectedEvent = Event::find($eventId);
        $this->eventId = $eventId;
        $this->exhibitor = $exhibitorData;
        // $this->selectedEvent = $selectedEvent;
    }

    public function selectedVisitor($id)
    {
        $this->visitor = Visitor::where('id', $id)->first();
    }

    public function mount(Request $request)
    {
        $this->eventId = $request->eventId;

        if ($this->eventId) {
            $selectedEvent = Event::where('id', $this->eventId)->select('start_date', 'end_date')->first();
            $start = Carbon::parse($selectedEvent->start_date);
            $end = Carbon::parse($selectedEvent->end_date);
            for ($date = $start; $date->lte($end); $date->addDay()) {
                $formattedDate = [
                    'display' => $date->format('M-D-d'),
                    'value' => $date->format('Y-m-d'),
                ];
                $this->dateList[] = $formattedDate;
            }
        }

        auth()->guard('exhibitor')->check() ? $this->exhibitorId = auth()->guard('exhibitor')->user()->id : '';
        auth()->guard('visitor')->check() ? $this->visitorId = auth()->guard('visitor')->user()->id : '';
    }

    public function saveAppointment()
    {
        $this->validate();
        $createdBy = auth()->guard()->check() ? auth()->guard()->user()->id : null;

        $result =  Appointment::create([
            'visitor_id' => $this->visitorId ?? $this->visitor->id,
            'exhibitor_id' => $this->exhibitor->id ?? $this->selectedExhibitorId,
            'event_id' => $this->eventId,
            'scheduled_at' => $this->scheduledAt . $this->time,
            'notes' => $this->notes,
            'status' => 'scheduled',
            'created_by' => $createdBy,
        ]);

        $visitor = Visitor::find($this->visitorId );
        $exhibitor =Exhibitor::find($this->exhibitor->id ?? $this->selectedExhibitorId  );
        // dd($visitor,$this->visitor, $visitor->name ?? $this->visitor->name ?? '',$this->exhibitor,$this->selectedExhibitorId,$exhibitor->name);

        if ($result) {
            $notificationPayload = [
                'senderName' => $visitor->name ?? $this->visitor->name ?? '',
                'receiverName' => $this->exhibitor->name ?? $exhibitor->name ?? '',
                'scheduledAt' => Carbon::parse($result->scheduled_at)->toFormattedDateString(),
                'status' => ucfirst($result->status),
            ];

            $exhibitor = Exhibitor::with('exhibitorContact')->find($this->exhibitor->id ?? $this->selectedExhibitorId );
            $exhibitorContactPersonMobileNumber = $exhibitor->exhibitorContact->contact_number ?? null;
            $emailData = [
                'receiverEmail' => $exhibitor->email,
                'appointmentId' => $result->id,
            ];
            sendAppointmentInitNotification($exhibitorContactPersonMobileNumber, $notificationPayload);
            sendAppointmentStatusChangeEmail($emailData, $notificationPayload);

            $this->closeModal();

            $this->dispatch('message', 'success', 'Your appointment scheduled successfully');
            return;
        }
        $this->dispatch('message', 'error', 'Something went wrong');
        return;
    }

    public function update()
    {
        $this->validate();
        try {
            $this->appointmentData = Appointment::find($this->appointmentId);
            $this->appointmentData->update([
                'scheduled_at' => $this->scheduledAt . $this->time,
                'notes' => $this->notes,
                'status' => 'rescheduled',
                'updated_by' => null,
            ]);

            $visitorName = $this->appointmentData->visitor->name ?? '';
            $exhibitorName = $this->appointmentData->exhibitor->name ?? '';
            $scheduledAt = $this->appointmentData->scheduled_at ?? '';
            $scheduledAt = Carbon::parse($scheduledAt)->format('d M Y h:i A');

            $isVisitor = auth()->guard('visitor')->check();
            $isExhibitor = auth()->guard('exhibitor')->check();
            $isOrganizer = isOrganizer();
            $emailData = [
                'receiverEmail' => $isVisitor ? $this->appointmentData->exhibitor->email : $this->appointmentData->visitor->email,
                'appointmentId' => $this->appointmentId,
            ];

            if ($isOrganizer || $isExhibitor) {
                sendAppointmentStatusChangeEmail($emailData, [
                    'senderName' => $exhibitorName,
                    'receiverName' => $visitorName,
                    'status' => ucfirst($this->appointmentData->status),
                    'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
                ]);
            }

            if ($isOrganizer || $isVisitor) {
                sendAppointmentStatusChangeEmail($emailData, [
                    'senderName' => $visitorName,
                    'receiverName' => $exhibitorName,
                    'status' => ucfirst($this->appointmentData->status),
                    'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
                ]);
            }
            $isUpdated = $this->appointmentData->wasChanged(['scheduled_at', 'notes', 'status']);
            if ($isUpdated) {
                $this->dispatch('showAlertListener', 'success', 'Schedule Successfully Updated');
            } else {
                $this->dispatch('showAlertListener', 'info', 'Do some Modification to be update');
            }
        } catch (\Exception $e) {
            session()->flash("error", $e->getMessage());
            return;
        }
    }

    public function render()
    {
        $exhibitors = Exhibitor::where('deleted_by', null)->select('id', 'name')->get();
        // dd($exhibitors);

        return view('livewire.appointments-modal', ['exhibitors' => $exhibitors]);
    }

    public function closeModal()
    {
        $this->reset([
            'scheduledAt',
            'time',
            'selectedExhibitorId',
            'notes',
        ]);
        $this->resetErrorBag();
        $this->dispatch('closeModal');
    }

}
