<?php

namespace App\Http\Controllers;

use App\Jobs\SendWelcomeNotificationThorughWhatsappBotJob;
use App\Models\Appointment;
use App\Models\EventExhibitor;
use App\Models\EventVisitor;
use App\Models\Visitor;

class MigrationController extends Controller
{
    public function sendVisitorsWelcomeNotification()
    {
        $visitors = Visitor::get();

        foreach ($visitors as $visitorIndex => $visitor) {
            dispatch(new SendWelcomeNotificationThorughWhatsappBotJob($visitor))
                ->onQueue('visitor_welcome_notification')
                ->delay(now()->addSeconds(10));
            echo ($visitorIndex + 1) . " . sent to $visitor->name <br>";
        }
        // HOw to trigger the queue
        echo 'All visitors welcome notification dispatched to queue.';
        echo '<br>';
        echo 'Now run the queue worker to send the welcome notification to the visitors.';
        echo '<br>';
    }

    public function updateEventProductsInStringFormat()
    {
        $eventExhibitors = EventExhibitor::get();

        foreach ($eventExhibitors as $eventExhibitor) {

            $productIds = $eventExhibitor->products;

            $productIds = array_map(function ($productId) {
                return strval($productId);
            }, $productIds);

            $eventExhibitor->products = $productIds;
            $eventExhibitor->save();
        }

        echo 'All event exhibitors products updated to string format.';
    }

    public function updateDesignationFieldIn10TVisitorsTable()
    {
        $data = readCSV(public_path('/assets/10t-visitors-dec-23.csv'));

        $updatedCount = 0;
        foreach ($data as $visitorInfo) {
            $mobileNumber = $visitorInfo['Phone'] ?? '';
            $designation = $visitorInfo['Designation'] ?? '';

            $visitor = Visitor::where('mobile_number', $mobileNumber)->first();

            if ($visitor) {
                $visitor->designation = $designation;
                $visitor->save();
                $updatedCount++;
            }
        }

        echo 'Total ' . $updatedCount . ' visitors designation updated.<br>';
    }

    public function registeringVisitorsWhoMakesAppointmentsWithoutRegister()
    {
        $eventId = 12;

        $visitorIds = Appointment::select('visitor_id')->where('event_id', $eventId)
            ->where('visitor_id', '>', 0)
            ->groupBy('visitor_id')
            ->pluck('visitor_id')
            ->toArray();

        foreach ($visitorIds as $visitorId) {
            $exitsInEvent = EventVisitor::where('event_id', $eventId)
                ->where('visitor_id', $visitorId)
                ->first();

            if ($exitsInEvent) {
                continue;
            }

            $visitor = Visitor::find($visitorId);
            if ($visitor) {
                $eventVisitor = new EventVisitor();
                $eventVisitor->event_id = $eventId;
                $eventVisitor->visitor_id = $visitorId;
                $eventVisitor->is_visited = 1;
                $eventVisitor->save();
            }
        }
        return 'All visitors registered successfully.';
    }
}
