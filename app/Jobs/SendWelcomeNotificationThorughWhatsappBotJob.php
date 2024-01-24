<?php

namespace App\Jobs;

use App\Models\Visitor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendWelcomeNotificationThorughWhatsappBotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $visitor;
    /**
     * Create a new job instance.
     */
    public function __construct($visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sentWelcomeNotification = $this->visitor->_meta['is_welcome_notification_sent'] ?? '';
        $sentWelcomeNotification = (strtolower($sentWelcomeNotification) == 'yes') ? true : false;
        $mobileNumber = $this->visitor->mobile_number ?? '';
        if (!$sentWelcomeNotification && $mobileNumber != '') {
            $result = sendWelcomeMessageThroughWhatsappBot($mobileNumber, 'visitor');
            $status = $result['status'] ?? '';
            if ($status == 'success') {
                $visitorId = $this->visitor->id ?? '';
                Visitor::where('id', $visitorId)->update([
                    '_meta->is_welcome_notification_sent' => 'yes'
                ]);
            }
        }
    }
}
