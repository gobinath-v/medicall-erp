<?php

namespace App\Http\Livewire\Import;

use Exception;
use App\Models\Product;
use Livewire\Component;
use App\Models\Exhibitor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Exhibitors extends Component
{

    protected $listeners = [
        "importingData" => 'import',
    ];

    public function render()
    {
        return view('livewire.import.exhibitors')->layout('layouts.admin');
    }

    public function import($importData)
    {
        $data = $importData['data'] ?? [];
        if (count($data) <= 1) {
            session()->flash('err_message', 'No data to import');
            return;
        }

        $insertedExhibitorsCount = 0;
        for ($ii = 1; $ii < count($data); $ii++) {
            $stallNo = $data[$ii][0];
            $companyName = $data[$ii][1];
            $address = $data[$ii][2];
            $city = $data[$ii][3];
            $state = $data[$ii][4];
            $country = $data[$ii][5];
            $pincode = $data[$ii][6];
            $officePhoneNo = $data[$ii][7];
            $contactPersonName = $data[$ii][8];
            $contactPersonCellPhoneNo = $data[$ii][9];
            $email = $data[$ii][10];
            $website = $data[$ii][11];
            $products = $data[$ii][12] ?? '';
            $picture = $data[$ii][13];
            $description = $data[$ii][14] ?? '';

            $profileName = Str::replace(' ', '', $companyName);
            $profileName = $profileName . rand(0, 9999);

            $emailIds = explode('/', $email);
            $email = $emailIds[0] ?? '';
            $email = trim($email);
            $alternateEmail = $emailIds[1] ?? '';
            $alternateEmail = trim($alternateEmail);

            if (empty($email)) {
                continue;
            }

            $productIds = [];

            if ($products) {
                $products = explode(',', $products);
                foreach ($products as $product) {

                    if (strlen($product) >= 189) {
                        continue;
                    }

                    $product = trim($product);
                    $product = strtolower($product);
                    $product = Product::firstOrCreate(['name' => $product]);
                    $productIds[] = strval($product->id);
                }
            }
            $exhibitor = Exhibitor::where('email', $email)->first();
            $currentEvent = getCurrentEvent();

            if ($exhibitor) {
                $registeredInCurrentEvent = $exhibitor->eventExhibitors()->where('event_id', $currentEvent->id)->first();
                if ($registeredInCurrentEvent) {
                    continue;
                }

                foreach ($productIds as $productId) {
                    $exhibitor->exhibitorProducts()->create([
                        'exhibitor_id' => $exhibitor->id,
                        'product_id' => intval($productId),
                    ]);
                }

                $exhibitor->eventExhibitors()->create([
                    'event_id' => $currentEvent->id ?? 0,
                    'exhibitor_id' => $exhibitor->id,
                    'stall_no' => $stallNo,
                    'is_sponsorer' => 0,
                    'products' => $productIds,
                    'is_active' => 1, // set active by default
                ]);

                $insertedExhibitorsCount++;

                continue;
            }


            $exhibitorData = [
                'salutation' => "Dr",
                'username' => $profileName,
                'name' => $companyName,
                'email' => $email,
                'designation' => "",
                'mobile_number' => $officePhoneNo,
                'password' => Hash::make(config('app.default_user_password')),
                'registration_type' => 'import-online',
                'description' => $description,
                'website' => $website,
                '_meta'  => [
                    'alternate_email' => $alternateEmail,
                ]
            ];

            $exhibitor = Exhibitor::create($exhibitorData);

            sendWelcomeMessageThroughWhatsappBot($exhibitor->mobile_number, 'exhibitor');

            $exhibitor->exhibitorContact()->create([
                'salutation' => "Mr",
                'name' => $contactPersonName,
                'contact_number' => $contactPersonCellPhoneNo
            ]);

            $exhibitor->address()->create([
                'address' => $address,
                'pincode' => $pincode,
                'city' => $city ?? null,
                'state' => $state ?? null,
                'country' => $country ?? null,
            ]);

            foreach ($productIds as $productId) {
                $exhibitor->exhibitorProducts()->create([
                    'exhibitor_id' => $exhibitor->id,
                    'product_id' => intval($productId),
                ]);
            }

            $exhibitor->eventExhibitors()->create([
                'event_id' => $currentEvent->id ?? 0,
                'exhibitor_id' => $exhibitor->id,
                'stall_no' => $stallNo,
                'is_sponsorer' => 0,
                'products' => $productIds,
                'is_active' => 1, // set active by default
            ]);

            $insertedExhibitorsCount++;
        }


        session()->flash('success', 'Imported ' . $insertedExhibitorsCount . ' exhibitors');

        return redirect()->route('import.exhibitors');
    }
}