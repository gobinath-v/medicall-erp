<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GreetingNotificationToParticipatedVisitorsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $payload = [];
    /**
     * Create a new job instance.
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $sendTo = $this->payload['send_to'] ?? '';

        if ($sendTo == '') {
            return;
        }
        $result = $this->sendNotification();
    }

    public function sendNotification()
    {
        $url = 'https://api.engati.com/whatsapp-api/v1.0/customer/68259/bot/0d10a092bb054b35/template';

        $header = $this->payload['eventTitle'] ?? 'Medicall';
        $header = $header . " - Day 1";
        try {
            $payload = [
                "payload" => [
                    "name" => "feedbacktrigger",
                    "components" => [
                        [
                            "type" => "header",
                            "parameters"  => [
                                [
                                    "type" => "text",
                                    "text" => $header
                                ],
                            ]
                        ],
                        [
                            "type" => "body",
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $this->payload['eventTitle'] ?? ''
                                ],
                                [
                                    "type" => "text",
                                    "text" => $this->payload['year'] ?? ''
                                ]
                            ]
                        ],
                        [
                            "index" => 0,
                            "parameters" => [
                                [
                                    "payload" => "flow_E0EC01C600144B718D7B7874450A2DC5",
                                    "type" => "payload"
                                ]
                            ],
                            "sub_type" => "quick_reply",
                            "type" => "button"
                        ]
                    ],
                    "language" => [
                        "code" => "en_US",
                        "policy" => "deterministic"
                    ],
                    "namespace" => "dd96ee84_2909_4425_86ba_8a7fb56c2d2a"
                ],
                "phoneNumber" => $this->payload['send_to']
            ];

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_POST => 1,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => json_encode($payload),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Basic a29ad3e1-2bb5-4126-9d44-153e96330a54-G4TG8QD",
                    "Content-Type: application/json",
                ),
            ));

            $response = curl_exec($curl);

            $isCurlError = curl_error($curl);

            curl_close($curl);

            if ($isCurlError) {
                return ['status' => 'fail', 'message' => $isCurlError];
            }
            $response = json_decode($response, true);
            $sent = $response['status']['code'] ?? '';
            if ($sent == 1000) {
                return ['status' => 'success', 'message' => 'Sent successfully', 'response' => $response];
            }
            return ['status' => 'fail', 'message' => 'Failed to send', 'response' => $response];
        } catch (\Exception $e) {
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }
}
