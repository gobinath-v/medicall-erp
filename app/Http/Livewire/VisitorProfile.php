<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Visitor;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class VisitorProfile extends Component
{
    use WithFileUploads;
    public $photo;
    public $visitorData;
    public $products;
    public $product_looking = [];
    public $eventId;
    public $isDisabled = true;
    public $countries = [];

    public $visitor = [
        'username' => '',
        'salutation' => 'Mr',
        'name' => '',
        'mobile_number' => '',
        'email' => '',
        'category_id' => '',
        'organization' => '',
        'designation' => '',
        'pincode',
        'city' => '',
        'state' => '',
        'country' => 'India',
        'address' => '',

    ];

    public $categories;
    public $productData;
    public $visitorId;
    // public $events;

    protected $rules = [
        'visitor.name' => 'required',
        'visitor.email' => 'required|string|email',
        'visitor.category_id' => 'required',
        'visitor.organization' => 'required',
        'visitor.designation' => 'required',

        'visitor.pincode' => 'required',

    ];

    protected $messages = [
        'visitor.name.required' => 'The Name field is required.',
        'visitor.email.required' => 'The email field is required.',
        'visitor.organization.required' => 'The Organization field is required',
        'visitor.designation.required' => 'The Designation field is required',
        'visitor.pincode.required' => 'Enter valid pincode/zipcode',
        'visitor.category_id.required' => 'Select Nature of Business'
    ];

    public $knowSources = [
        "brochure" => "Brochure",
        "bus_panel" => "Bus Panel",
        "emailers" => "Emailers",
        "facebook_instagram" => "Facebook/Instagram",
        "field_force" => "Field Force",
        "hoardings" => "Hoardings",
        "hotline" => "Hotline",
        "i_know_medicall" => "I Know Medicall",
        "internet_search" => "Internet Search",
        "linkedin" => "Linkedin",
        "newspaper_ad" => "Newspaper Ad",
        "outdoor" => "Outdoor",
        "promoters" => "Promoters",
        "sms" => "SMS",
        "team_medicall" => "Team Medicall",
        "tele-calling" => "Tele-Calling",
        "tele-marketing" => "Tele-Marketing",
        "through_exhibitors" => "Through Exhibitors",
        "twitter" => "Twitter",
        "whatsApp" => "WhatsApp",
        "word-of-mouth" => "Word-of-mouth",
    ];

    public function mount()
    {
        $this->visitorId = auth()->guard('visitor')->user()->id;
        $this->visitorData = Visitor::find($this->visitorId);

        if ($this->visitor) {
            $this->visitor = [
                'avatar' => $this->visitorData->_meta ?? '',
                'username' => $this->visitorData->username ?? '',
                'salutation' => $this->visitorData->salutation ?? '',
                'name' => $this->visitorData->name ?? '',
                'mobile_number' => $this->visitorData->mobile_number ?? '',
                'email' => $this->visitorData->email ?? '',
                'category_id' => $this->visitorData->category_id ?? '',
                'organization' => $this->visitorData->organization ?? '',
                'designation' => $this->visitorData->designation ?? '',
                'pincode' => $this->visitorData->address->pincode ?? '',
                'city' => $this->visitorData->address->city ?? '',
                'state' => $this->visitorData->address->state ?? '',
                'country' => ucwords(strtolower($this->visitorData->address->country)) ?? '',
                'address' => $this->visitorData->address->address ?? '',
            ];

        }
        $this->categories = Category::where('type', 'visitor_business_type')->where('is_active', 1)->get();
        $this->productData = Product::pluck('name', 'id');
        $this->countries = getCountries();
    }
    public function visitorDetailsUpdate()
    {

        $this->validate();

        $VisitorEmailExists = Visitor::where('email', $this->visitor['email'])
            ->where('id', '!=', $this->visitorData['id'])
            ->first();
        if ($VisitorEmailExists) {
            $this->addError('visitor.email', 'Email already exists');
            return;
        }

        try {
            // $visitor = Visitor::find($this->visitorId);
            // $selectedProducts = $this->visitor['product_looking'] ?? [];

            // $visitor->eventVisitors()->update(['product_looking' => $selectedProducts]);
            $this->visitorData->update([

                'salutation' => $this->visitor['salutation'],
                'name' => $this->visitor['name'],
                'email' => $this->visitor['email'],
                'category_id' => $this->visitor['category_id'],
                'organization' => $this->visitor['organization'],
                'designation' => $this->visitor['designation'],

            ]);

            $this->visitorData->address()->update([
                'address' => $this->visitor['address'],
                'pincode' => $this->visitor['pincode'],
                'city' => $this->visitor['city'] ?? null,
                'state' => $this->visitor['state'] ?? null,
                'country' => $this->visitor['country'],
            ]);

            $this->visitorData->update(['updated_by' => null]);

            session()->flash('success', 'Visitor updated successfully.');
            redirect()->route('visitor.profile');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return;
        }
    }

    public function update()
    {
        try {
            if ($this->photo) {
                if ($this->visitorData['_meta'] !== null) {
                    $filepath = public_path('storage/' . $this->visitorData['_meta']);
                    if (file_exists($filepath)) {
                        unlink($filepath);
                    }
                }
                $imageFolderPath = 'visitor/' . date('Y/m');
                $imageName = $this->photo->getClientOriginalName();
                $imagePath = $this->photo->storeAs($imageFolderPath, $imageName, 'public');
                $this->visitor['_meta'] = $imagePath;
            }

            $this->visitorData->update([
                '_meta' => $this->visitor['_meta'],
            ]);
            $isUpdated = $this->visitorData->wasChanged('_meta');
            $this->photo = null;
            if ($isUpdated) {
                session()->flash('success', 'Visitor profile updated successfully');
                return redirect()->route('visitor.profile');
            }
            session()->flash('info', 'Change Image to update');
            return;
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function updateVisitorProducts()
    {
        try {
            $this->validate([
                'product_looking' => 'required|array',
            ]);

            $selectedProducts = $this->product_looking;

            $eventVisitor = $this->visitorData->eventVisitors()->firstOrNew(['event_id' => $this->eventId]);

            $eventVisitor->product_looking = $selectedProducts;
            $eventVisitor->save();

            session()->flash('success', 'Products Updated Successfully.');
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return;
        }

        $this->closeModal();
    }

    public function getEventId($eventId)
    {
        $this->eventId = $eventId;

        $visitorEvent = $this->visitorData->eventVisitors()->where('event_id', $eventId)->first();

        $this->product_looking = $visitorEvent ? $visitorEvent->product_looking : [];

        $this->dispatch('showProducts', $this->product_looking);
    }

    public function render()
    {
        return view('livewire.visitor-profile')->layout('layouts.admin');
    }
    public function closeModal()
    {
        $this->resetErrorBag();
        $this->dispatch('closeModal');
    }

    public function editProfile()
    {
        $this->isDisabled = false;
    }

    public function backToProfile()
    {
        redirect()->route('visitor.profile');
    }

    public function pincode()
    {
        if (strtolower($this->visitor['country']) == 'india' && isset($this->visitor['pincode'])) {
            // You can call getPincodeData directly here or adjust it as needed.
            $pincodeData = getPincodeData($this->visitor['pincode']);

            if ($pincodeData['state'] === null && $pincodeData['city'] === null) {
                $this->addError("visitor.pincode", "Pincode is not Exists");
            } else {
                $this->resetErrorBag('visitor.pincode');
                $this->visitor['state'] = $pincodeData['state'];
                $this->visitor['city'] = $pincodeData['city'];
            }
        }
    }

    public function clearAddressFields()
    {
        $this->visitor['city'] = null;
        $this->visitor['state'] = null;
        $this->visitor['pincode'] = null;
    }
}
