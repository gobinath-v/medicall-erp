<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class ExhibitorEventDashboardController extends Controller
{

    public function getEventDashboardData(Request $request, $eventId)
    {
        $exhibitorId = auth()->user()->id;

        try {

            $totalAppointmentCount = Appointment::where('event_id', $eventId)->where('exhibitor_id', $exhibitorId)->count();
            $scheduledCount = Appointment::where('event_id', $eventId)->where('exhibitor_id', $exhibitorId)->where('status', 'scheduled')->count();
            $rescheduledCount = Appointment::where('event_id', $eventId)->where('exhibitor_id', $exhibitorId)->where('status', 'rescheduled')->count();
            $confirmedCount = Appointment::where('event_id', $eventId)->where('exhibitor_id', $exhibitorId)->where('status', 'confirmed')->count();
            $lapsedCount = Appointment::where('event_id', $eventId)->where('exhibitor_id', $exhibitorId)->where('status', 'no-show')->count();
            $cancelledCount = Appointment::where('event_id', $eventId)->where('exhibitor_id', $exhibitorId)->where('status', 'cancelled')->count();
            $completedCount = Appointment::where('event_id', $eventId)->where('exhibitor_id', $exhibitorId)->where('status', 'completed')->count();
            $pendingAppointments = Appointment::where('exhibitor_id', $exhibitorId)
                ->where('event_id', $eventId)
                ->whereIn('status', ['scheduled', 'rescheduled'])
                ->with('visitor')
                ->select('id', 'visitor_id', 'scheduled_at', 'notes')
                ->get();
            $formattedPendingAppointments = [];
            foreach ($pendingAppointments as $appointment) {
                $formattedPendingAppointments[] = [
                    'id' => $appointment->id,
                    'visitor_name' => $appointment->visitor->name ?? '',
                    'organization' => $appointment->visitor->organization,
                    'place' => $appointment->visitor->address->place,
                    'purpose' => $appointment->notes,
                    'scheduled_date_time' => $appointment->scheduled_at->isoFormat('llll'),
                    'confirm_appointment' => $appointment->status == 'confirmed',
                ];
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Exhibitor Dashboard',
                'data' => [
                    'total_appointments_count' => $totalAppointmentCount,
                    'scheduled_count' => $scheduledCount,
                    'rescheduled_count' => $rescheduledCount,
                    'confirmed_count' => $confirmedCount,
                    'lapsed_count' => $lapsedCount,
                    'cancelled_count' => $cancelledCount,
                    'completed_count' => $completedCount,
                    'pending_appoinments' => $formattedPendingAppointments,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went wrong', 'error' => $e->getMessage()], 500);
        }
    }
}
