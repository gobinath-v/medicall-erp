<?php

namespace App\Http\Controllers\api;

use DateTime;
use Carbon\Carbon;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function showAppointments(Request $request)
    {
        $eventId = $request->eventId;
        $authId = auth()->user()->id;
        try {
            if (!$authId) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Exhibitor Id Not Found',
                ]);
            }

            $appointments = Appointment::where('event_id', $eventId)
                ->where('exhibitor_id', $authId)->orderBy('scheduled_at', 'desc')
                ->get();

            if (!(isset($appointments) && count($appointments) > 0)) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'There is No Appointments Available'
                ]);
            }

            $formattedAppointments = $appointments->map(function ($appointment) {
                return [
                    'appointment_id' => $appointment->id,
                    'event_id' => $appointment->event->id ?? '',
                    'event_title' => $appointment->event->title ?? '',
                    'visitor_id' => $appointment->visitor->id ?? '',
                    'visitor_name' => $appointment->visitor->name ?? '',
                    'exhibitor_id' => $appointment->exhibitor->id ?? '',
                    'exhibitor_name' => $appointment->exhibitor->name ?? '',
                    'exhibitor_designation' => $appointment->exhibitor->exhibitorContact->designation ?? '',
                    'exhibitor_city' => $appointment->exhibitor->address->city ?? '',
                    'exhibitor_feedback_message' => $appointment->_meta['exhibitor_feedback']['message'] ?? '',
                    'exhibitor_feedback_timestamp' => $appointment->_meta['exhibitor_feedback']['timestamp'] ?? '',
                    'visitor_feedback_message' => $appointment->_meta['visitor_feedback']->message ?? '',
                    'visitor_feedback_timestamp' => $appointment->_meta['visitor_feedback']->timestamp ?? '',
                    'scheduled_on' => $appointment->scheduled_at ?? '',
                    'status' => $appointment->status ?? '',
                    'notes' => $appointment->notes ?? '',

                ];
            });

            return response()->json(['status' => 'success', 'data' => $formattedAppointments]);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    public function statusUpdate(Request $request)
    {
        $appointmentId = $request->appointmentId;
        $status = $request->status;
        $eventId = $request->eventId;
        $exhibitorId = auth()->user()->id;
        $date = $request->date;
        $time = $request->time;
        $scheduledAt = $date . $time;
        $exhibitorFeedbackMessage = $request->feedback;

        if (!$appointmentId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Appointment Id Missing',
            ]);
        }
        if (!$status) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Status is Missing',
            ]);
        }
        if (!$eventId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Event Id is Missing',
            ]);
        }
        if (!$exhibitorId) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Exhibitor Id is Missing',
            ]);
        }
        // if ($status === 'rescheduled' && !$date) {
        //     return response()->json([
        //         'status' => 'failed',
        //         'message' => 'Date is Missing, Date is Required',
        //     ]);
        // }
        // if ($status === 'rescheduled' && !$time) {
        //     return response()->json([
        //         'status' => 'failed',
        //         'message' => 'Time is Missing, Time is Required',
        //     ]);
        // }
        if ($status === 'completed' && !$exhibitorFeedbackMessage) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Feedback is required',
            ]);
        }

        try {
            $appointments = Appointment::find($appointmentId);
            $currentEventId = $appointments['event_id'] == $eventId;
            $currentExhibitorId = $appointments['exhibitor_id'] == $exhibitorId;

            $emailData = [
                'receiverEmail' => $appointments->visitor->email,
                'appointmentId' => $appointmentId,
            ];

            if ($currentEventId && $currentExhibitorId) {
                if ($status === 'rescheduled') {
                    $startDate = new DateTime(getCurrentEvent()->start_date);
                    $endDate = new DateTime(getCurrentEvent()->end_date);

                    $validation = Validator::make($request->all(),
                        [
                            'date' => "required|date_format:Y-m-d|after_or_equal:{$startDate->format('Y-m-d')}|before_or_equal:{$endDate->format('Y-m-d')}",
                            'time' => 'required|date_format:H:i|after_or_equal:10:00|before_or_equal:18:00',
                        ]
                    );

                    if ($validation->fails()) {
                        return response()->json([
                            'status' => 'Validation failed',
                            'message' => 'The given date and time is not valid',
                            'errors' => $validation->errors()->all(),
                        ]);
                    }

                        $appointments->update([
                            'scheduled_at' => $scheduledAt,
                            'status' => $status,
                        ]);
                        sendAppointmentStatusChangeNotification($appointments->visitor->mobile_number, 'visitor', [
                            'senderName' => $appointments->exhibitor->name,
                            'receiverName' => $appointments->visitor->name,
                            'status' => ucfirst($status),
                            'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
                        ]);
                        sendAppointmentStatusChangeEmail($emailData, [
                            'senderName' => $appointments->exhibitor->name,
                            'receiverName' => $appointments->visitor->name,
                            'status' => ucfirst($status),
                            'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
                        ]);

                } elseif ($status === 'completed') {

                    $exhibitorFeedback = [
                        "message" => $exhibitorFeedbackMessage,
                        "timestamp" => now()->format('Y-m-d H:i:s'),
                    ];

                    $appointments->update([
                        '_meta' => [
                            "exhibitor_feedback" => $exhibitorFeedback,
                        ],
                        'status' => $status,
                    ]);

                    $isUpdated = $appointments->wasChanged('_meta', 'status');
                    if ($isUpdated) {
                        sendAppointmentStatusChangeNotification($appointments->visitor->mobile_number, 'visitor', [
                            'senderName' => $appointments->exhibitor->name,
                            'receiverName' => $appointments->visitor->name,
                            'status' => ucfirst($status),
                            'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
                        ]);
                        sendAppointmentStatusChangeEmail($emailData, [
                            'senderName' => $appointments->exhibitor->name,
                            'receiverName' => $appointments->visitor->name,
                            'status' => ucfirst($status),
                            'scheduledAt' => Carbon::parse($scheduledAt)->toDayDateTimeString(),
                        ]);
                    }
                } elseif (in_array($status, ['scheduled', 'confirmed', 'cancelled', 'no-show'])) {
                    $appointments->update([
                        'status' => $status,
                    ]);
                } else {
                    return response()->json([
                        'status' => 'failed',
                        'message' => 'Check the status ' . $status . ' does not exist',
                    ]);
                }

                $formattedAppointments = [
                    'appointment_id' => $appointments->id,
                    'event_id' => $appointments->event->id ?? '',
                    'event_title' => $appointments->event->title ?? '',
                    'visitor_id' => $appointments->visitor->id ?? '',
                    'visitor_name' => $appointments->visitor->name ?? '',
                    'exhibitor_id' => $appointments->exhibitor->id ?? '',
                    'exhibitor_name' => $appointments->exhibitor->name ?? '',
                    'exhibitor_designation' => $appointments->exhibitor->exhibitorContact->designation ?? '',
                    'exhibitor_city' => $appointments->exhibitor->address->city ?? '',
                    'exhibitor_feedback_message' => $appointments->_meta['exhibitor_feedback']['message'] ?? '',
                    'exhibitor_feedback_timestamp' => $appointments->_meta['exhibitor_feedback']['timestamp'] ?? '',
                    'visitor_feedback_message' => $appointments->_meta['visitor_feedback']->message ?? '',
                    'visitor_feedback_timestamp' => $appointments->_meta['visitor_feedback']->timestamp ?? '',
                    'scheduled_on' => $appointments->scheduled_at ?? '',
                    'status' => $appointments->status ?? '',
                    'notes' => $appointments->notes ?? '',

                ];
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No Appointments for this exhibitor in this event',

                ]);
            }

            return response()->json([
                'status' => 'success',
                'data' => $formattedAppointments,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
