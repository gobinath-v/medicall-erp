<?php

namespace App\Http\Livewire\Import;

use App\Models\Visitor;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendWelcomeInvitationJob;

class Visitors extends Component
{

    public function insertMissing10TVisitors()
    {
        // Only For Dec 23 Visitors
        $file = public_path('/assets/10t-visitors-dec-23.csv');
        $data = readCSV($file);

        $insertedVisitorsCount = 0;
        $alreadyRegisteredCount = 0;
        $notInsertedVisitorsCount = 0;
        $currentEvent = getCurrentEvent();
        $defaultPassword = config('app.default_user_password');

        foreach ($data as $visitorInfo) {
            $salutation = $visitorInfo['Salutation'] ?? 'Mr';
            $salutation = (strtolower($salutation) == 'manager') ? 'Mr' : $salutation;
            $name = $visitorInfo['Name'] ?? '';

            $username = $this->getUniqueUsernameFromGivenName($name);

            $mobileNumber = $visitorInfo['Phone'] ?? '';
            $email = $visitorInfo['Email'] ?? '';
            $organization = $visitorInfo['Company'] ?? '';
            $designation = $visitorInfo['Designation'] ?? '';
            $source = '10t';
            $city = $visitorInfo['City'] ?? '';
            $country = $visitorInfo['Country'] ?? '';
            $dateOfRegistration = $visitorInfo['Date of Register'] ?? '';
            $meta = [
                'dateOfRegistration' => $dateOfRegistration ?? '',
                'is_welcome_notification_sent' => 'no',
            ];
            $visitor = Visitor::where('mobile_number', $mobileNumber)->first();

            if ($visitor) {
                $eventVisitor = $visitor->eventVisitors()->where('event_id', $currentEvent->id ?? 0)->first();
                if ($eventVisitor) {
                    $alreadyRegisteredCount++;
                    continue;
                }
                // register visitor for current event
                $visitor->eventVisitors()->create([
                    'event_id' => $currentEvent->id ?? 0,
                ]);

                $insertedVisitorsCount++;
                continue;
            }

            $visitorData = [
                'salutation' => $salutation,
                'username' => $username,
                'password' => Hash::make($defaultPassword),
                'name' => $name,
                'mobile_number' => $mobileNumber,
                'email' => $email,
                'organization' => $organization,
                'designation' => $designation,
                'registration_type' => $source,
                '_meta' => $meta,
            ];
            $visitor = Visitor::create($visitorData);

            if ($visitor) {

                $visitor->address()->create([
                    'address' => $city,
                    'city' => $city,
                    'country' => $country,
                ]);

                $visitor->eventVisitors()->create([
                    'event_id' => $currentEvent->id ?? 0,
                ]);

                dispatch(new SendWelcomeInvitationJob([
                    'mobileNumber' => $mobileNumber,
                    'type' => 'visitor',
                ]))
                    ->onQueue('visitor_welcome_notification')
                    ->delay(now()->addSeconds(3));

                // update meta
                $meta = $visitor->_meta;
                $meta['is_welcome_notification_sent'] = 'yes';
                $visitor->_meta = $meta;
                $visitor->save();

                $insertedVisitorsCount++;
            } else {
                $notInsertedVisitorsCount++;
            }
        }

        echo "Total visitors imported: " . $insertedVisitorsCount;
        echo "<br>";
        echo "Total visitors already registered: " . $alreadyRegisteredCount;
        echo "<br>";
        echo "Total visitors not inserted: " . $notInsertedVisitorsCount;
    }


    private function getUniqueUsernameFromGivenName($givenName)
    {
        $username = str_replace(' ', '-', $givenName);
        $username = strtolower($username);
        $username = $username . '-' . rand(10000, 99999999);
        while (Visitor::where('username', $username)->first()) {
            $username = $username . '-' . rand(10000, 99999999);
        }
        return $username;
    }


    public function import()
    {
        $file = public_path('/assets/dec-23-visitors-part-1.csv');
        // read file line by line
        $file = fopen($file, 'r');
        $header = fgetcsv($file);
        $data = [];
        while ($row = fgetcsv($file)) {
            $data[] = array_combine($header, $row);
        }
        fclose($file);

        $insertedVisitorsCount = 0;
        $alreadyRegisteredCount = 0;
        $notInsertedVisitorsCount = 0;
        $currentEvent = getCurrentEvent();
        $defaultPassword = config('app.default_user_password');
        foreach ($data as $visitorInfo) {
            $salutation = $visitorInfo['Salutation'] ?? 'Mr';
            $salutation = (strtolower($salutation) == 'manager') ? 'Mr' : $salutation;
            $name = $visitorInfo['name'] ?? '';

            $username = str_replace(' ', '-', $name);
            $username = strtolower($username);
            $username = $username . '-' . rand(1000, 9999);

            while (Visitor::where('username', $username)->first()) {
                $username = $username . '-' . rand(1000, 9999);
            }

            $mobileNumber = $visitorInfo['number'] ?? '';
            $email = $visitorInfo['email'] ?? '';
            $organization = $visitorInfo['company'] ?? '';
            $designation = $visitorInfo['designation'] ?? '';
            $source = $visitorInfo['source'] ?? 'web';
            $timestamp = $visitorInfo['timestamp'] ?? '';
            $timestamp = !empty($timestamp) ? date('Y-m-d H:i:s', strtotime($timestamp)) : '';

            $visitor = Visitor::where('mobile_number', $mobileNumber)->first();

            if ($visitor) {
                $eventVisitor = $visitor->eventVisitors()->where('event_id', $currentEvent->id ?? 0)->first();
                if ($eventVisitor) {
                    $alreadyRegisteredCount++;
                    continue;
                }
                // register visitor for current event
                $visitor->eventVisitors()->create([
                    'event_id' => $currentEvent->id ?? 0,
                ]);

                // sendWelcomeMessageThroughWhatsappBot($mobileNumber, 'visitor');
                $insertedVisitorsCount++;
                continue;
            }


            $visitorData = [
                'salutation' => $salutation,
                'username' => $username,
                'password' => Hash::make($defaultPassword),
                'name' => $name,
                'mobile_number' => $mobileNumber,
                'email' => $email,
                'organization' => $organization,
                'designation' => $designation,
                'registration_type' => $source,
            ];
            if (!empty($timestamp)) {
                $visitorData['created_at'] = $timestamp;
                $visitorData['_meta'] = [
                    'registeredAt' => $timestamp,
                    'sendWelcomeMessage' => 'no'
                ];
            }

            $visitor = Visitor::create($visitorData);

            if ($visitor) {
                $visitor->eventVisitors()->create([
                    'event_id' => $currentEvent->id ?? 0,
                ]);
                $insertedVisitorsCount++;
            } else {
                $notInsertedVisitorsCount++;
            }
        }

        echo "Total visitors imported: " . $insertedVisitorsCount;
        echo "<br>";
        echo "Total visitors already registered: " . $alreadyRegisteredCount;
        echo "<br>";
        echo "Total visitors not inserted: " . $notInsertedVisitorsCount;
    }
    public function render()
    {
        return view('livewire.import.visitors');
    }
}
