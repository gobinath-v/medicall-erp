<?php

namespace App\Http\Controllers;

use App\Jobs\GreetingNotificationToParticipatedVisitorsJob;
use App\Models\Visitor;
use App\Models\Exhibitor;
use App\Jobs\RemainderNotificationJob;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendRemainderNotificationsToAllUsers(Request $request)
    {
        $type = $request->type ?? 'all';
        $types = explode(',', $type);
        $canSendToVisitors = in_array('visitors', $types) || in_array('all', $types);
        $canSendToExhibitors = in_array('exhibitors', $types) || in_array('all', $types);
        $canSendToExhibitorContactPersons = in_array('exhibitor_contact_persons', $types) || in_array('all', $types);

        if ($canSendToVisitors) {

            $visitors = Visitor::get();
            foreach ($visitors as $visitorIndex => $visitor) {

                $payload = [
                    'name' => $visitor->name,
                    'mobile_number' => $visitor->mobile_number,
                    'user_type' => 'visitor',
                    'send_to' => $visitor->mobile_number,
                ];

                dispatch(new RemainderNotificationJob($payload))
                    ->onQueue('remainder_notification_for_visitors')
                    ->delay(now()->addSeconds(1));
            }
        }

        if ($canSendToExhibitorContactPersons || $canSendToExhibitors) {
            $exhibitors = Exhibitor::with('exhibitorContact')->get();

            foreach ($exhibitors as $exhibitorIndex => $exhibitor) {

                $contactPersonMobileNumber = $exhibitor->exhibitorContact->contact_number ?? '';

                if ($canSendToExhibitorContactPersons) {

                    dispatch(new RemainderNotificationJob([
                        'name' => $exhibitor->name,
                        'mobile_number' => $exhibitor->mobile_number ?? '',
                        'user_type' => 'exhibitor',
                        'send_to' => $contactPersonMobileNumber
                    ]))
                        ->onQueue('remainder_notification_for_exhibitor_contact_persons')
                        ->delay(now()->addSeconds(1));
                }

                if ($canSendToExhibitors) {

                    dispatch(new RemainderNotificationJob([
                        'name' => $exhibitor->name,
                        'mobile_number' => $exhibitor->mobile_number ?? '',
                        'user_type' => 'exhibitor',
                        'send_to' => $exhibitor->mobile_number
                    ]))
                        ->onQueue('remainder_notification_for_exhibitors')
                        ->delay(now()->addSeconds(1));
                }
            }
        }


        return ['status' => 'success', 'message' => 'Queued successfully'];
    }

    public function sendGreetingsNotificationsToParticipatedVisitors(Request $request)
    {
        $fileName = $request->file_name ?? '';

        $filePublicPath = public_path('assets/' . $fileName);

        if (!file_exists($filePublicPath)) {
            return response()->json(['status' => 'error', 'message' => 'File not found or incorrect name given'], 404, [], JSON_PRETTY_PRINT);
        }

        $visitors = readCSV($filePublicPath);
        $currentEvent = getCurrentEvent();
        $currentEventTitle = $currentEvent->title ?? '';
        $queuedCount = 0;

        foreach ($visitors as $visitorIndex => $visitor) {

            $name = $visitor['name'] ?? '';
            $mobileNumber = $visitor['number'] ?? '';

            if (empty($mobileNumber)) {
                continue;
            }

            $payload = [
                'name' => $name,
                'eventTitle' => $currentEventTitle,
                'send_to' => $mobileNumber,
                'year' => date('Y'),
            ];

            dispatch(new GreetingNotificationToParticipatedVisitorsJob($payload))
                ->onQueue('send_greetings_to_participated_visitors')
                ->delay(now()->addSeconds(1));

            $queuedCount++;
        }

        $payload = [
            'name' => "Sekar",
            'eventTitle' => $currentEventTitle,
            'send_to' => "9787480936",
            'year' => date('Y'),
        ];

        dispatch(new GreetingNotificationToParticipatedVisitorsJob($payload))
            ->onQueue('send_greetings_to_participated_visitors')
            ->delay(now()->addSeconds(1));
        $queuedCount++;
        return response()->json(['status' => 'success', 'message' => 'Queued successfully', 'queued_count' => $queuedCount], 200, [], JSON_PRETTY_PRINT);
    }
}
