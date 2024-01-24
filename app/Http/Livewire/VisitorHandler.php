<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Visitor;
use Livewire\Component;

class VisitorHandler extends Component
{
    public $visitor = [
        'username' => '',
        'salutation' => 'Mr',
        'name' => '',
        'mobile_number' => '',
        'email' => '',
        'category_id' => '',
        'organization' => '',
        'designation' => '',
        'known_source' => '',
        'reason_for_visit' => '',
        'newsletter' => 1,
        'proof_type' => 'pan',
        'proof_id' => null,
        'registration_type' => 'web',
        'product_looking' => [],
        // 'event_id' => '',
    ];

    public $categories;
    public $products;
    public $visitorId;
    // public $events;

    protected $rules = [
        // 'visitor.username' => 'required',
        'visitor.name' => 'required|regex:/^[a-zA-Z ]+$/',
        'visitor.email' => 'required|string|email',
        'visitor.mobile_number' => 'required|digits:10',
        'visitor.organization' => 'required',
        'visitor.designation' => 'required',
    ];

    protected $messages = [
        // 'visitor.username.required' => 'The Username field is required.',
        'visitor.name.required' => 'The Name field is required.',
        'visitor.name.regex' => 'Enter valid Name',
        'visitor.email.required' => 'Email field is required.',
        'visitor.mobile_number.required' => 'The mobile.no field is required.',
        'visitor.mobile_number.digits' => 'Please give the valid mobile no',
        'visitor.organization.required' => 'Please select Organization',
        'visitor.designation.required' => 'Please select Designation',
    ];

    public function update()
    {
        $this->validate();

        $VisitorEmailExists = Visitor::where('email', $this->visitor['email'])
            ->where('id', '!=', $this->visitor['id'])
            ->first();
        if ($VisitorEmailExists) {
            $this->addError('visitor.email', 'Email already exists');
            return;
        }

        $visitorPhoneNoExists = Visitor::where('mobile_number', $this->visitor['mobile_number'])
            ->where('id', '!=', $this->visitor['id'])
            ->first();
        if ($visitorPhoneNoExists) {
            $this->addError('visitor.mobile_number', 'Phone number already exists.');
            return;
        }

        try {
            $visitor = Visitor::find($this->visitorId);
            // $selectedProducts = $this->visitor['product_looking'] ?? [];

            // $visitor->eventVisitors()->update(['product_looking' => $selectedProducts]);
            $visitor->update($this->visitor);
            $authData = getAuthData();

            if (isset($authData->id)) {
                $authId = $authData->id;
                $visitor->update(['updated_by' => $authId]);
            }
            session()->flash('success', 'Visitor updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return;
        }
    }

    public function mount($visitorId)
    {
        $this->visitorId = $visitorId;
        $this->categories = Category::where('type', 'visitor_business_type')->where('is_active', 1)->get();
        $this->products = Product::all();
        $visitor = Visitor::find($visitorId);
        // $this->events = Event::orderBy('start_date', 'asc')->get();
        if (!$visitor) {
            session()->flash('error', 'Visitor not found');
            return redirect()->route('visitors.summary');
        }

        $this->visitor = $visitor->toArray();
    }

    public function editVisitor($visitorData)
    {
        $this->visitor = $visitorData;
    }
    public function render()
    {
        return view('livewire.visitor-handler')
            ->layout('layouts.admin');
    }
}
