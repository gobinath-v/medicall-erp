<?php

namespace App\Http\Livewire\Exhibitor;

use App\Models\Exhibitor;
use Livewire\Component;

class ProfileStatus extends Component
{

    public $exhibitor = [];

    public $exhibitorId;
    public $filledFields;

    public $percentageFields = [
        'salutation' => 5,
        'name' => 6,
        'designation' => 5,
        'contact_number' => 6,
        'logo' => 7,
        'username' => 7,
        'company_name' => 5,
        'category_id' => 5,
        'products' => 8,
        'email' => 6,
        'mobile_number' => 6,
        'website_url' => 4,
        'description' => 5,
    ];

    public $exhibitorAddress = [
        'pincode' => 5,
        'city' => 5,
        'state' => 5,
        'country' => 5,
        'address' => 5,
    ];
    public $address = [
        'pincode' => 9,
        'country' => 8,
        'address' => 8,
    ];

    public function mount()
    {
        if (!empty($this->exhibitorId)) {
            $exhibitor = Exhibitor::find($this->exhibitorId);
        } else {
            $exhibitor = getAuthData();
        }

        $this->exhibitor['logo'] = $exhibitor->logo;
        $this->exhibitor['salutation'] = $exhibitor->exhibitorContact->salutation?? '';
        $this->exhibitor['name'] = $exhibitor->exhibitorContact->name?? '';
        $this->exhibitor['designation'] = $exhibitor->exhibitorContact->designation?? '';
        $this->exhibitor['contact_number'] = $exhibitor->exhibitorContact->contact_number?? '';
        $this->exhibitor['username'] = $exhibitor->username;
        $this->exhibitor['company_name'] = $exhibitor->name;
        $this->exhibitor['category_id'] = $exhibitor->category->id?? '';
        $this->exhibitor['email'] = $exhibitor->email;
        $this->exhibitor['mobile_number'] = $exhibitor->mobile_number;
        $this->exhibitor['pincode'] = $exhibitor->address->pincode ?? '';
        $this->exhibitor['city'] = $exhibitor->address->city ?? '';
        $this->exhibitor['state'] = $exhibitor->address->state ?? '';
        $this->exhibitor['country'] = $exhibitor->address->country ?? '';
        $this->exhibitor['address'] = $exhibitor->address->address ?? '';
        $this->exhibitor['products'] = $exhibitor->exhibitorProducts->pluck('product_id');
        $this->exhibitor['website_url'] = $exhibitor->_meta['website_url'] ?? null;
        $this->exhibitor['description'] = $exhibitor->description ?? null;

        $this->countFilledFields();
    }

    public function countFilledFields()
    {
        $this->filledFields = 0;

        foreach ($this->percentageFields as $field => $percentage) {

            if (!empty($this->exhibitor[$field])) {
                $this->filledFields += $percentage;
            }
        }
        foreach ($this->exhibitorAddress as $field => $percentage) {
            if ($this->exhibitor['country'] === 'India' && !empty($this->exhibitor[$field])) {
                $this->filledFields += $percentage;
            }
        }
        foreach ($this->address as $field => $percentage) {
            if ($this->exhibitor['country'] !== 'India' && !empty($this->exhibitor[$field])) {
                $this->filledFields += $percentage;
            }
        }
    }

    public function render()
    {
        return view('livewire.exhibitor.profile-status');
    }
}
