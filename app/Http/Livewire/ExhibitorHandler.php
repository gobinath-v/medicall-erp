<?php

namespace App\Http\Livewire;

use App\Models\ExhibitorContact;
use Illuminate\Support\Facades\Http;
use App\Models\Exhibitor;
use App\Models\Address;
use App\Models\EventExhibitor;
use App\Models\ExhibitorProduct;
use App\Models\Event;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Illuminate\Http\Request;

class ExhibitorHandler extends Component {
    public $showPassword = false;
    public $categories = [];
    public $products = [];
    public $events = [];
    public $username_exists = false;
    public $suggestedValue;
    public $countries = [];
    public $profile_name;
    public $eventId;

    public $exhibitor = [
        'salutation' => 'Dr',
        'name' => '',
        'designation' => '',
        'contact_number' => '',
        'username' => '',
        'company_name' => '',
        'category_id',
        'newsletter' => false,
        'products' => [],
        'email' => '',
        'mobile_number' => '',
        'known_source',
        'registration_type' => 'web',
        'pincode',
        'city',
        'state',
        'country' => 'India',
        'address' => '',
        'event_id' => '',
    ];

    protected $rules = [
        'exhibitor.event_id' => 'required',
        'exhibitor.salutation' => 'required',
        'exhibitor.name' => 'required|regex:/^[a-zA-Z ]+$/',
        'exhibitor.designation' => 'required',
        'exhibitor.contact_number' => 'required|digits:10|unique:exhibitor_contacts,contact_number|regex:/^[0-9]{10}$/',
        'exhibitor.username' => 'required|string|unique:exhibitors,username',
        'exhibitor.company_name' => 'required',
        'exhibitor.category_id' => 'required',
        'exhibitor.products' => 'required',
        'exhibitor.email' => 'required|email|unique:exhibitors,email',
        'exhibitor.mobile_number' => 'required|unique:exhibitors,mobile_number|regex:/^[0-9]*$/',
        'exhibitor.known_source' => 'required',
        'exhibitor.country' => 'required',
        'exhibitor.pincode' => 'required',
        'exhibitor.address' => 'required',
    ];
    protected $messages = [
        'exhibitor.event_id.required' => 'Event is required',
        'exhibitor.salutation.required' => 'Salutation is required',
        'exhibitor.name.required' => 'Name is required',
        'exhibitor.name.regex' => 'Enter valid name',
        'exhibitor.designation.required' => 'Designation is required',
        'exhibitor.contact_number.required' => 'Contact number is required',
        'exhibitor.contact_number.digits' => 'Enter valid contact number',
        'exhibitor.contact_number.unique' => 'Contact number already exists',
        'exhibitor.contact_number.regex' => 'Enter valid contact number',
        'exhibitor.username.required' => 'Username is required',
        'exhibitor.username.unique' => 'Username already exists',
        'exhibitor.company_name.required' => 'Company name is required',
        'exhibitor.category_id.required' => 'Business type is required',
        'exhibitor.products.required' => 'Products is required',
        'exhibitor.email.required' => 'Email is required',
        'exhibitor.email.email' => 'Enter valid email',
        'exhibitor.email.unique' => 'Email already exists',
        'exhibitor.mobile_number.required' => 'Contact number is required',
        'exhibitor.mobile_number.unique' => 'Company number already exists',
        'exhibitor.mobile_number.regex' => 'Enter valid contact number',
        'exhibitor.known_source.required' => 'Known source is required',
        'exhibitor.country.required' => 'Country is required',
        'exhibitor.pincode.required' => 'Pincode/Zipcode is required',
        'exhibitor.address.required' => 'Address is required',

    ];

    public function mount(Request $request)
    {
        $this->eventId = request()->eventId;
        $this->categories = Category::where('type', 'exhibitor_business_type')
            ->where('is_active', 1)
            ->get();
        $this->products = Product::pluck('name', 'id');
        $this->events = Event::where('start_date', '>=', now()->format('Y-m-d'))
            ->orWhere('end_date', '>', now()->format('Y-m-d'))
            ->pluck('title', 'id');
        $this->countries = getCountries();
        $this->exhibitor['event_id'] = isset($this->eventId) ? $this->eventId : '';
    }

    public function render() {
        if(auth()->guard('exhibitor')->check() || auth()->guard('web')->check()) {
            return view('livewire.exhibitor-handler')->layout('layouts.admin');
        }
        return view('livewire.exhibitor-handler')->layout('layouts.guest');
    }

    public function save() {
        $this->validate();
        $this->username_exists = false;
        try {
            DB::beginTransaction();
            $productList = $this->exhibitor['products'] ?? [];
            $selectedProducts = [];
            foreach($productList as $product) {

                if((int)$product) {
                    $selectedProducts[] = $product;
                } else {

                    // Add New Propduct to master
                    $newProduct = Product::create([
                        'name' => $product
                    ]);

                    $selectedProducts[] = (string)$newProduct->id;
                }
            }
            $exhibitor = Exhibitor::create([
                'username' => $this->exhibitor['username'],
                'name' => $this->exhibitor['company_name'],
                'category_id' => $this->exhibitor['category_id'],
                'email' => $this->exhibitor['email'],
                'mobile_number' => $this->exhibitor['mobile_number'],
                'known_source' => $this->exhibitor['known_source'],
                'registration_type' => $this->exhibitor['registration_type'],
                'newsletter' => $this->exhibitor['newsletter'],
                'password' => Hash::make(config('app.default_user_password')),
            ]);


            $exhibitor->exhibitorContact()->create([
                'salutation' => $this->exhibitor['salutation'],
                'name' => $this->exhibitor['name'],
                'contact_number' => $this->exhibitor['contact_number'],
                'designation' => $this->exhibitor['designation'],
            ]);

            $exhibitor->eventExhibitors()->create([
                'event_id' => $this->exhibitor['event_id'],
                'products' => $selectedProducts,
            ]);


            $exhibitor->address()->create([
                'address' => $this->exhibitor['address'],
                'pincode' => $this->exhibitor['pincode'],
                'city' => $this->exhibitor['city'] ?? null,
                'state' => $this->exhibitor['state'] ?? null,
                'country' => $this->exhibitor['country'],
            ]);

            foreach($selectedProducts as $productId) {

                $exhibitor->exhibitorProducts()->create([
                    'product_id' => $productId,
                ]);
            }

            $authData = getAuthData();
            if($authData !== null) {
                $exhibitor->update(['created_by' => $authData->id]);
            } else {
                $exhibitor->update(['created_by' => null]);
            }

            // TODO: Send Welcome Message
            sendWelcomeMessageThroughWhatsappBot($this->exhibitor['mobile_number'], 'exhibitor');

            DB::commit();
            // dd(isset($this->eventId),$this->eventId);
            session()->flash('success', 'Exhibitor Registered Successfully.');
            return redirect(route('exhibitor.registration',isset($this->eventId) ? ['eventId' => $this->eventId] : ''));
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', $e->getMessage());
            return;
        }
    }

    public function checkUserName() {
        $this->username_exists = true;
        $this->validate([
            'exhibitor.username' => 'string|unique:exhibitors,username',
        ]);

        $this->username_exists = false;
    }

    public function getCompanyName() {
        $this->profile_name = Str::replace(' ', '', $this->exhibitor['company_name']);
        $this->exhibitor['username'] = $this->profile_name;
        $this->suggestedValue = $this->profile_name.rand(0, 9999);
    }
    public function setSuggestedValue() {
        $this->exhibitor['username'] = $this->suggestedValue;
        $this->checkUserName();
    }

    public function clearLocationFields() {
        $this->exhibitor['city'] = null;
        $this->exhibitor['state'] = null;
        $this->exhibitor['pincode'] = null;
    }
    public function pincode() {
        if(strtolower($this->exhibitor['country']) == 'india' && isset($this->exhibitor['pincode'])) {
            $pincodeData = getPincodeData($this->exhibitor['pincode']);
            if($pincodeData['state'] === null && $pincodeData['city'] === null) {
                $this->addError("exhibitor.pincode", "Pincode is not Exists");
                $this->exhibitor['state'] = null;
                $this->exhibitor['city'] = null;
            } else {
                $this->resetErrorBag("exhibitor.pincode");
                $this->exhibitor['state'] = $pincodeData['state'];
                $this->exhibitor['city'] = $pincodeData['city'];
            }
        }
    }

    public function resetFields() {
        $this->reset();
    }
}
