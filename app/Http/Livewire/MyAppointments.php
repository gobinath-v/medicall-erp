<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
// use Illuminate\Support\Facades\Auth;
use App\Models\Exhibitor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;

class MyAppointments extends Component
{
    use WithPagination;

    public $paginationTheme = 'bootstrap';

    public $perPage = 10;

    public $toggleContent = false;

    public $search, $dateFilter;

    public $sortApplied = false;

    public $sortcolumnName = 'scheduled_at';

    public $sortDirection = 'desc';

    public $sortByUserOrder, $sortByUserColumnName;

    public $appointmentId, $eventId, $visitorId, $exhibitorId;
    public $feedback;

    public $appointmentStatus;
    public $feedbackType;

    protected $listeners = [
        'showAlertListener' => 'showAlert',
    ];

    public function showAlert($status, $message)
    {
        $this->dispatch('closeModal');
        session()->flash($status, $message);
    }
    public function sortBy($columnName, $order)
    {
        $this->sortDirection = $order;
        $this->sortcolumnName = $columnName;
    }

    public function sortByUsers($columnName, $order)
    {
        $this->sortByUserOrder = $order;
        $this->sortByUserColumnName = $columnName;
    }

    public function resetField()
    {
        return $this->search = null;
    }

    public function resetDate()
    {
        return $this->dateFilter = null;
    }


    public function toggleBtn()
    {
        $this->toggleContent = !$this->toggleContent;
    }

    public function getAppointmentId($appointmentId)
    {
        $this->appointmentId = $appointmentId;
        $this->dispatch('openModel');
        $this->dispatch('getAppointmentIdListener', $this->appointmentId);
    }
    public function exhibitorAppointmentId($appointmentId, $status)
    {
        $this->appointmentId = $appointmentId;
        $this->feedbackType = $status;
    }
    public function appointmentComplete()
    {
        $appointment = Appointment::find($this->appointmentId);
        $meta = $appointment->_meta ?? null;

        if ($this->feedbackType == 'completed') {
            $this->validate([
                'feedback' => 'required',
            ]);
        }
        $feedbackData = [
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'message' => $this->feedback,
        ];

        $isVisitor = auth()->guard('visitor')->check();
        $isExhibitor = auth()->guard('exhibitor')->check();
        $isOrganizer = isOrganizer();


        if ($isVisitor && !empty($feedbackData['message'])) {
            $meta['visitor_feedback'] = $feedbackData;
        } else if ($isExhibitor && !empty($feedbackData['message'])) {
            $meta['exhibitor_feedback'] = $feedbackData;
        }

        $appointment->status = 'completed';
        $appointment->_meta = $meta;
        $appointment->save();

        $this->closeFeedbackModal();

        $visitorName = $appointment->visitor->name ?? '';
        $exhibitorName = $appointment->exhibitor->name ?? '';
        $scheduledAt = $appointment->scheduled_at ?? '';
        $scheduledAt = Carbon::parse($scheduledAt)->format('d M Y h:i A');
        $emailData = [
            'receiverEmail' => $isVisitor ? $appointment->exhibitor->email : $appointment->visitor->email,
            'appointmentId' => $this->appointmentId,
        ];

        if (($isOrganizer || $isExhibitor) && $this->feedbackType == 'completed') {
            sendAppointmentStatusChangeNotification($appointment->visitor->mobile_number, 'visitor', [
                'senderName' => $exhibitorName,
                'receiverName' => $visitorName,
                'status' => ucfirst($appointment->status),
                'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
            ]);
            sendAppointmentStatusChangeEmail($emailData, [
                'senderName' => $exhibitorName,
                'receiverName' => $visitorName,
                'status' => ucfirst($appointment->status),
                'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
            ]);
        }

        if (($isOrganizer || $isVisitor) && $this->feedbackType == 'completed') {

            $exhibitor = Exhibitor::with('exhibitorContact')->find($appointment->exhibitor->id);
            $exhibitorContactPersonMobileNumber = $exhibitor->exhibitorContact->contact_number ?? null;
            sendAppointmentStatusChangeNotification($exhibitorContactPersonMobileNumber, 'exhibitor', [
                'senderName' => $visitorName,
                'receiverName' => $exhibitorName,
                'status' => ucfirst($appointment->status),
                'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
            ]);
            sendAppointmentStatusChangeEmail($emailData, [
                'senderName' => $visitorName,
                'receiverName' => $exhibitorName,
                'status' => ucfirst($appointment->status),
                'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
            ]);
        }


        $this->feedback = '';
        $isUpdated = $appointment->wasChanged('_meta');
        if ($isUpdated) {
            $this->showAlert('success', 'Updated successfully');
        }
    }
    public function closeFeedbackModal()
    {
        $this->resetErrorBag();
        $this->dispatch('closeFeedbackModal');
    }
    public function exhibitorAppointmentStatus($appoinmentId, $status)
    {
        $appointment = Appointment::find($appoinmentId);
        $appointment->status = $status;
        $appointment->save();

        $visitorName = $appointment->visitor->name ?? '';
        $exhibitorName = $appointment->exhibitor->name ?? '';
        $scheduledAt = $appointment->scheduled_at ?? '';
        $scheduledAt = Carbon::parse($scheduledAt)->format('d M Y h:i A');

        $isVisitor = auth()->guard('visitor')->check();
        $isExhibitor = auth()->guard('exhibitor')->check();
        $isOrganizer = isOrganizer();
        $emailData = [
            'receiverEmail' => $isVisitor ? $appointment->exhibitor->email : $appointment->visitor->email,
            'appointmentId' => $appoinmentId,
        ];

        if ($isOrganizer || $isExhibitor) {
            sendAppointmentStatusChangeNotification($appointment->visitor->mobile_number, 'visitor', [
                'senderName' => $exhibitorName,
                'receiverName' => $visitorName,
                'status' => ucfirst($status),
                'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
            ]);
            sendAppointmentStatusChangeEmail($emailData, [
                'senderName' => $exhibitorName,
                'receiverName' => $visitorName,
                'status' => ucfirst($status),
                'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
            ]);
        }

        if ($isOrganizer || $isVisitor) {
            $exhibitor = Exhibitor::with('exhibitorContact')->find($appointment->exhibitor->id);
            $exhibitorContactPersonMobileNumber = $exhibitor->exhibitorContact->contact_number ?? null;

            sendAppointmentStatusChangeNotification($exhibitorContactPersonMobileNumber, 'exhibitor', [
                'senderName' => $visitorName,
                'receiverName' => $exhibitorName,
                'status' => ucfirst($status),
                'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
            ]);
            sendAppointmentStatusChangeEmail($emailData, [
                'senderName' => $visitorName,
                'receiverName' => $exhibitorName,
                'status' => ucfirst($status),
                'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
            ]);
        }

        $this->showAlert('success', 'Appointment status updated successfully');
    }
    public function mount(Request $request)
    {
        $this->eventId = $request->eventId;
        $this->appointmentStatus = $request->appointmentStatus;
        $this->visitorId = auth()->guard('visitor')->check() ? auth()->guard('visitor')->user()->id : null;
        $this->exhibitorId = auth()->guard('exhibitor')->check() ? auth()->guard('exhibitor')->user()->id : null;
    }

    public function render()
    {

        $query = Appointment::query();

        if (auth()->guard('visitor')->check()) {
            $query->where('event_id', $this->eventId)
                ->where('visitor_id', $this->visitorId)
                ->when(isset($this->appointmentStatus), function ($status) {
                    $status->where('status', $this->appointmentStatus);
                });

            if ($this->search !== null) {
                $query->whereHas('exhibitor', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->dateFilter !== null) {
                $query->whereDate('scheduled_at', $this->dateFilter);
            }

            if ($this->sortcolumnName === 'exhibitor_name') {
                $query->join('exhibitors', 'appointments.exhibitor_id', '=', 'exhibitors.id')
                    ->orderBy('exhibitors.name', $this->sortDirection)
                    ->select('appointments.*');
            }
        } elseif (auth()->guard('exhibitor')->check()) {
            $query->where('event_id', $this->eventId)
                ->where('exhibitor_id', $this->exhibitorId)
                ->when(isset($this->appointmentStatus), function ($status) {
                    $status->where('status', $this->appointmentStatus);
                });

            if ($this->search !== null) {
                $query->whereHas('visitor', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                });
            }

            if ($this->dateFilter !== null) {
                $query->whereDate('scheduled_at', $this->dateFilter);
            }

            if ($this->sortcolumnName === 'visitor_name') {
                $query->join('visitors', 'appointments.visitor_id', '=', 'visitors.id')
                    ->orderBy('visitors.name', $this->sortDirection)
                    ->select('appointments.*');
            }
        }

        // Apply sorting only if sort parameters are set
        if ($this->sortcolumnName && $this->sortDirection && !in_array($this->sortcolumnName, ['exhibitor_name', 'visitor_name'])) {
            $query->orderBy($this->sortcolumnName, $this->sortDirection);
        }

        $myappointments = $query->paginate($this->perPage);

        return view('livewire.my-appointments', [
            'myappointments' => $myappointments,
        ])->layout('layouts.admin');
    }

    public function generateICS($appointmentId)
    {
        $icsContent= generateICSFile($appointmentId);
        $fileName = 'medicall' . $appointmentId . '.ics';
        return response()->stream(
            function () use ($icsContent) {
                echo $icsContent;
            },
            200,
            [
                'Content-Type' => 'text/calendar; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]
        );
    }
    public function updated($propertyName)
    {
        if ($propertyName === 'search' || $propertyName === 'dateFilter') {
            $this->resetPage();
        }
    }

}
