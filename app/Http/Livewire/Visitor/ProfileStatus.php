<?php

namespace App\Http\Livewire\Visitor;

use App\Models\Visitor;
use Livewire\Component;

class ProfileStatus extends Component
{

    public $visitor = [];
    public $visitorId;
    public $filledFields;

    public $percentageVisitorField = [
        'username' => 9,
        'salutation' => 5,
        'name' => 9,
        'mobile_number' => 8,
        'email' => 9,
        'category_id' => 8,
        'organization' => 7,
        'designation' => 7,
        '_meta' => 10,

    ];

    public $visitorAddress = [
        'pincode' => 7,
        'city' => 5,
        'state' => 5,
        'country' => 5,
        'address' => 6,
    ];
    public $address = [
        'pincode' => 10,
        'country' => 8,
        'address' => 10,
    ];

    public function mount()
    {
        if (!empty($this->visitorId)) {
            $visitor = Visitor::find($this->visitorId);
        } else {
            $visitor = getAuthData();
        }
        $this->visitor = [
            'username' => $visitor->username ?? '',
            'salutation' => $visitor->salutation ?? '',
            'name' => $visitor->name ?? '',
            'mobile_number' => $visitor->mobile_number ?? '',
            'email' => $visitor->email ?? '',
            'category_id' => $visitor->category_id ?? '',
            'organization' => $visitor->organization ?? '',
            'designation' => $visitor->designation ?? '',
            'pincode' => $visitor->address->pincode ?? '',
            'city' => $visitor->address->city ?? '',
            'state' => $visitor->address->state ?? '',
            'country' => $visitor->address->country ?? '',
            'address' => $visitor->address->address ?? '',
            '_meta' => $visitor->_meta ?? [],

        ];
        $this->countFilledFields();
    }

    public function countFilledFields()
    {
        $this->filledFields = 0;

        foreach ($this->percentageVisitorField as $field => $percentage) {

            if (!empty($this->visitor[$field])) {
                $this->filledFields += $percentage;
            }
        }
        foreach ($this->visitorAddress as $field => $percentage) {
            if ($this->visitor['country'] === 'India' && !empty($this->visitor[$field])) {
                $this->filledFields += $percentage;
            }
        }
        foreach ($this->address as $field => $percentage) {
            if ($this->visitor['country'] !== 'India' && !empty($this->visitor[$field])) {
                $this->filledFields += $percentage;
            }
        }

    }
    public function render()
    {
        return view('livewire.visitor.profile-status');
    }
}
