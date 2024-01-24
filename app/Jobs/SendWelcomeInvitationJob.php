<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeInvitationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $data = $this->data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mobileNumber = $this->data['mobileNumber'] ?? '';
        $type = $this->data['type'] ?? '';
        if ($mobileNumber != '' && $type != '') {
            sendWelcomeMessageThroughWhatsappBot($mobileNumber, $type);
        }
    }
}
