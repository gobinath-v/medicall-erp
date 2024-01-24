<?php

namespace App\Http\Livewire;

use App\Models\Address;
use App\Models\Category;
use App\Models\Event;
use App\Models\Product;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Livewire\Component;

class VisitorRegistration extends Component {
    public $visitor = [
        'username' => '',
        'salutation' => 'Dr',
        'name' => '',
        'mobile_number' => '',
        'email' => '',
        'category_id' => '',
        'organization' => '',
        'designation' => '',
        'known_source' => '',
        'reason_for_visit' => '',
        'newsletter' => 0,
        'proof_type' => '',
        'proof_id' => null,
        'registration_type' => 'web',
        'product_looking' => [],
        'event_id' => '',
    ];

    public $visitoraddress = [
        'pincode',
        'city' => '',
        'state' => '',
        'country' => 'India',
        'address' => '',
    ];
    public $categories;
    public $products;
    public $events;
    public $username_exists = false;
    public $profile_name;
    public $suggestedValue;
    public $countries = [];
    public $eventId;

    protected $rules = [
        'visitor.event_id' => 'required',
        'visitor.username' => 'required|string|unique:visitors,username',
        'visitor.name' => 'required|regex:/^[a-zA-Z ]+$/',
        'visitor.email' => 'required|string|email',
        'visitor.mobile_number' => 'required|digits:10',
        'visitor.designation' => 'required',
        'visitor.organization' => 'required',
        'visitoraddress.pincode' => 'required',
        'visitor.category_id' => 'required',

    ];

    protected $messages = [
        'visitor.event_id.required' => 'Event is required',
        'visitor.username.required' => 'The Username field is required.',
        'visitor.username.unique' => 'Username already exists.',
        'visitor.name.required' => 'The Name field is required.',
        'visitor.name.regex' => 'Enter valid Name',
        'visitor.email.required' => 'The email field is required.',
        'visitor.email.email' => 'Enter valid email.',
        'visitor.mobile_number.required' => 'The mobile number field is required.',
        'visitor.mobile_number.digits' => 'Please give the valid mobile number',
        'visitor.organization.required' => 'Please select your organization',
        'visitor.designation.required' => 'Please select designation',
        'visitoraddress.pincode.required' => 'Enter valid pincode/zipcode',
        'visitor.category_id' => 'The Nature of Business field is required',
    ];

    public function create() {
        $this->validate();

        $VisitorEmailExists = Visitor::where('email', $this->visitor['email'])->first(); // Corrected variable name

        if($VisitorEmailExists) {
            $this->addError('visitor.email', 'Visitor email address already exists'); // Corrected error message
            return;
        }

        $visitorPhoneNoExists = Visitor::where('mobile_number', $this->visitor['mobile_number'])->first();

        if($visitorPhoneNoExists) {
            $this->addError('visitor.mobile_number', 'visitor phone number already exists.');
            return;
        }

        $this->visitor['password'] = Hash::make(config('app.default_user_password'));
        try {
            DB::beginTransaction();
            $visitor = Visitor::create($this->visitor);

            if($visitor) {

                $address = new Address($this->visitoraddress);
                $visitor->address()->save($address);

                $selectedProducts = $this->visitor['product_looking'];

                $visitor->eventVisitors()->create(['product_looking' => $selectedProducts, 'event_id' => $this->visitor['event_id']]);

                //  $authData = isset(getAuthData()->id) ? getAuthData()->id :null;
                $authData = getAuthData();
                if($authData && isset($authData->user) && $authData->user->id) {
                    $authId = $authData->user->id;
                    $visitor->update(['created_by' => $authId]);
                }

                DB::commit();

                // TODO: Use job & queue to send welcome message
                sendWelcomeMessageThroughWhatsappBot($this->visitor['mobile_number'], 'visitor');

                session()->flash('success', 'Visitor created successfully');
                return redirect()->to(route('visitor-registration',isset($this->eventId) ? ['eventId' => $this->eventId] : ''));
            }
        } catch (\Exception $e) {
            DB::rollBack();

            session()->flash('error', $e->getMessage());
        }
    }

    public function mount(Request $request)
    {
        $this->eventId = request()->eventId;
        $currentDate = now();
        $this->categories = Category::where('type', 'visitor_business_type')->where('is_active', 1)->get();
        $this->products = Product::all();
        $this->events = Event::where('start_date', '>=', now()->format('Y-m-d'))
            ->orWhere('end_date', '>', now()->format('Y-m-d'))
            ->pluck('title', 'id');
        $this->countries = getCountries();
        $this->visitor['event_id'] = isset($this->eventId) ? $this->eventId : '';
    }

    public function pincode() {
        if(strtolower($this->visitoraddress['country']) == 'india' && isset($this->visitoraddress['pincode'])) {
            // You can call getPincodeData directly here or adjust it as needed.
            $pincodeData = getPincodeData($this->visitoraddress['pincode']);

            if($pincodeData['state'] === null && $pincodeData['city'] === null) {
                $this->addError("visitoraddress.pincode", "Pincode is not Exists");
            } else {
                $this->resetErrorBag('visitoraddress.pincode');
                $this->visitoraddress['state'] = $pincodeData['state'];
                $this->visitoraddress['city'] = $pincodeData['city'];
            }
        }
    }

    public function render() {
        if(auth()->guard('visitor')->check() || auth()->guard('web')->check()) {
            return view('livewire.visitor-registration')
                ->layout('layouts.admin');
        }
        return view('livewire.visitor-registration')
            ->layout('layouts.guest');
    }

    public function clearAddressFields() {
        $this->visitoraddress['city'] = null;
        $this->visitoraddress['state'] = null;
        $this->visitoraddress['pincode'] = null;
    }

    public function checkUserName() {
        $this->username_exists = true;
        $this->validate([
            'visitor.username' => 'string|unique:visitors,username',
        ]);

        $this->username_exists = false;
    }

    public function getProfileName() {
        $this->profile_name = str_replace(' ', '', $this->visitor['name']); // Replace spaces with underscores or any other character
        $this->visitor['username'] = $this->profile_name;
        $this->suggestedValue = $this->profile_name.rand(0, 9999);
    }

    public function setSuggestedValue() {
        $this->visitor['username'] = $this->suggestedValue;
        $this->checkUserName();
    }
}
