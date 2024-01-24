<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreviousEventController extends Controller
{
    public function showPreviousEvents()
    {
        try {
            $previousEventId = getPreviousEvents()->pluck('id')->toArray();
            $previousEvents = Event::whereIn('id', $previousEventId)->get();
            $formatedData = $previousEvents->map(function ($previousEvent) {
                return [
                    'event_id' => $previousEvent->id,
                    'event_title' => $previousEvent->title,
                    'start_date' => $previousEvent->start_date,
                    'end_date' => $previousEvent->end_date,
                    'event_layout' => $previousEvent->_meta['layout'],
                    'event_thumbnail' => $previousEvent->_meta['thumbnail'],
                ];
            });
            if ($previousEvents) {
                return response()->json([
                    'status' => 'success',
                    'data' => $formatedData,
                ]);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'data' => 'There is no previous event',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getPreviousEventCompletedAppointments(Request $request)
    {
        try {
            $eventId = $request->eventId;
            $exhibitorId = auth()->user()->id;

            if (!$eventId) {
                return response()->json([
                    'status' => 'failed',
                    'data' => 'Event Id is missing',
                ]);
            }

            $previousEventIds = getPreviousEvents()->pluck('id')->toArray();

            if (in_array($eventId, $previousEventIds)) {

                if (!$exhibitorId) {
                    return response()->json([
                        'status' => 'failed',
                        'data' => 'Exhibitor Id is missing',
                        'exhibitorId' => $exhibitorId,
                    ]);
                }

                $previousEventsAppointments = Appointment::whereIn('event_id', $previousEventIds)
                    ->where('exhibitor_id', $exhibitorId)->exists();

                if ($previousEventsAppointments) {

                    $appointments = Appointment::whereIn('event_id', $previousEventIds)
                        ->where('exhibitor_id', $exhibitorId)
                        ->where('status', 'completed')->get();

                    if (isset($appointments) && count($appointments) > 0) {
                        $formatedData = $appointments->map(function ($appointment) {
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

                        return response()->json([
                            'status' => 'success',
                            'data' => $formatedData,
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'failed',
                            'data' => 'There is no Completed Appointment for this exhibitor in this event',
                        ]);
                    }

                } else {
                    return response()->json([
                        'status' => 'failed',
                        'data' => 'There is no Appointment for this exhibitor in this event',
                    ]);
                }

            } else {
                return response()->json([
                    'status' => 'failed',
                    'data' => 'This Event does not exist in previous event list',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
