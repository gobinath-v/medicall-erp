<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RemainderNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userData;
    /**
     * Create a new job instance.
     */
    public function __construct($userData)
    {
        $this->userData = $userData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $result =  $this->sendRemainderNotification($this->userData['name'], $this->userData['mobile_number'], $this->userData['user_type'], $this->userData['send_to']);
    }

    public function sendRemainderNotification($name, $mobileNumber, $userType, $sendTo)
    {
        if (empty($mobileNumber) || empty($name) || empty($sendTo)) {
            return ['status' => 'fail', 'message' => 'Please fill the required fields'];
        }
        $url = 'https://api.engati.com/whatsapp-api/v1.0/customer/68259/bot/fad6d0cc73a045ac/template';
        $templateName = '1to1reminder_visitor';
        if ("exhibitor" == $userType) {
            $templateName = '1to1reminder_exhibitor';
            $url = 'https://api.engati.com/whatsapp-api/v1.0/customer/68259/bot/fad6d0cc73a045ac/template';
        }
        try {
            $payload = [
                "payload" => [
                    "name" => $templateName,
                    "components" => [
                        [
                            "type" => "body",
                            "parameters" => [
                                [
                                    "type" => "text",
                                    "text" => $name
                                ],
                                [
                                    "type" => "text",
                                    "text" => strval($mobileNumber)
                                ]
                            ]
                        ]
                    ],
                    "language" => [
                        "code" => "en_US",
                        "policy" => "deterministic"
                    ],
                    "namespace" => "dd96ee84_2909_4425_86ba_8a7fb56c2d2a"
                ],
                "phoneNumber" => $sendTo
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

            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return ['status' => 'fail', 'message' => $err];
            } else {
                return ['status' => 'success', 'message' => 'Sent successfully', 'response' => $response];
            }
        } catch (\Exception $e) {
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }
}
