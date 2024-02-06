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
use Illuminate\Support\Facades\DB;

class ViewExhibitor extends Component
{


    protected $paginationTheme = 'bootstrap';

    public $photo;
    public $
    public $exhibitor_products = [];
    public $eventId;
    public $categories = [];
    public $products = [];
    public $exhibitorData;
    public $isDisabled = true;
    public $countries = [];
    public $exhibitor = [
        'salutation' => 'Mr',
        'name' => '',
        'designation' => '',
        'contact_number' => '',
        'username' => '',
        'company_name' => '',
        'category_id',
        'products' => [],
        'email' => '',
        'mobile_number' => '',
        'pincode',
        'city',
        'state',
        'country' => 'India',
        'address' => '',
        'website_url' => '',
        'description' => '',
        'productImages' => '',
    ];
    public $productImage;
    public $authId;
    public $currentProductId;
    public $perPage = 10;

    protected $rules = [
        'exhibitor.salutation' => 'required',
        'exhibitor.name' => 'required|regex:/^[a-zA-Z ]+$/',
        'exhibitor.designation' => 'required',
        'exhibitor.contact_number' => 'required|digits:10|regex:/^[0-9]{10}$/',
        'exhibitor.company_name' => 'required',
        'exhibitor.category_id' => 'required',
        'exhibitor.products' => 'required',
        'exhibitor.email' => 'required|email',
        'exhibitor.country' => 'required',
        'exhibitor.pincode' => 'required',
        'exhibitor.address' => 'required',
    ];
    protected $messages = [
        'exhibitor.salutation.required' => 'Salutation is required',
        'exhibitor.name.required' => 'Name is required',
        'exhibitor.name.regex' => 'Enter valid name',
        'exhibitor.designation.required' => 'Designation is required',
        'exhibitor.contact_number.required' => 'Contact number is required',
        'exhibitor.contact_number.digits' => 'Enter valid contact number',
        'exhibitor.contact_number.regex' => 'Enter valid contact number',
        'exhibitor.company_name.required' => 'Company name is required',
        'exhibitor.category_id.required' => 'Business type is required',
        'exhibitor.products.required' => 'Products is required',
        'exhibitor.email.required' => 'Email is required',
        'exhibitor.email.email' => 'Enter valid email',
        'exhibitor.country.required' => 'Country is required',
        'exhibitor.pincode.required' => 'Pincode/Zipcode is required',
        'exhibitor.address.required' => 'Address is required',

    ];

    public function mount()
    {
        if (auth()->guard('exhibitor')->check()) {
            $this->authId = auth()->guard('exhibitor')->user()->id;
        }
        $this->exhibitorData = Exhibitor::find($this->authId);
        if ($this->exhibitorData) {
            $this->exhibitor['avatar'] = $this->exhibitorData->logo ?? null;
            $this->exhibitor['salutation'] = $this->exhibitorData->exhibitorContact->salutation ?? '';
            $this->exhibitor['name'] = $this->exhibitorData->exhibitorContact->name;
            $this->exhibitor['designation'] = $this->exhibitorData->exhibitorContact->designation ?? '';
            $this->exhibitor['contact_number'] = $this->exhibitorData->exhibitorContact->contact_number ?? '';
            $this->exhibitor['username'] = $this->exhibitorData->username ?? '';
            $this->exhibitor['company_name'] = $this->exhibitorData->name;
            $this->exhibitor['category_id'] = $this->exhibitorData->category->id ?? '';
            $this->exhibitor['email'] = $this->exhibitorData->email;
            $this->exhibitor['mobile_number'] = $this->exhibitorData->mobile_number ?? '';
            $this->exhibitor['pincode'] = $this->exhibitorData->address->pincode ?? '';
            $this->exhibitor['city'] = $this->exhibitorData->address->city ?? '';
            $this->exhibitor['state'] = $this->exhibitorData->address->state ?? '';
            $this->exhibitor['country'] = ucwords(strtolower($this->exhibitorData->address->country)) ?? '';
            $this->exhibitor['address'] = $this->exhibitorData->address->address ?? '';
            $this->exhibitor['products'] = $this->exhibitorData->exhibitorProducts->pluck('product_id') ?? [];
            $this->exhibitor['website_url'] = $this->exhibitorData->_meta['website_url'] ?? null;
            $this->exhibitor['description'] = $this->exhibitorData->description ?? null;
        }

        $this->categories = Category::where('type', 'exhibitor_business_type')
            ->where('is_active', 1)
            ->get();
        $this->products = Product::pluck('name', 'id');
        $this->countries = getCountries();
    }
    public function updateExhibitorDetails()
    {
        $this->validate();

        $exhibitorEmailExists = Exhibitor::where('email', $this->exhibitor['email'])->where('id', '!=', $this->exhibitorData['id'])->first();
        if ($exhibitorEmailExists) {
            $this->addError('exhibitor.email', 'Email already exists');
            return;
        }
        $exhibitorContactNoExists = ExhibitorContact::where('contact_number', $this->exhibitor['contact_number'])->where('exhibitor_id', '!=', $this->exhibitorData['id'])->first();
        if ($exhibitorContactNoExists) {
            $this->addError('exhibitor.contact_number', 'Contact number already exists');
            return;
        }

        try {
            DB::beginTransaction();
            $productList = $this->exhibitor['products'];
            $selectedProducts = [];
            foreach ($productList as $product) {

                if ((int) $product) {
                    $selectedProducts[] = $product;
                } else {

                    // Add New Propduct to master
                    $newProduct = Product::create([
                        'name' => $product
                    ]);

                    $selectedProducts[] = (string) $newProduct->id;
                }
            }

            $this->exhibitorData->update([

                'name' => $this->exhibitor['company_name'],
                'category_id' => $this->exhibitor['category_id'],
                'email' => $this->exhibitor['email'],
                'description' => $this->exhibitor['description'] ?? null,
                '_meta' => [
                    'website_url' => $this->exhibitor['website_url'] ?? null,
                ],

            ]);

            $this->exhibitorData->exhibitorContact()->update([
                'salutation' => $this->exhibitor['salutation'],
                'name' => $this->exhibitor['name'],
                'contact_number' => $this->exhibitor['contact_number'],
                'designation' => $this->exhibitor['designation'],
            ]);

            $this->exhibitorData->address()->update([
                'address' => $this->exhibitor['address'],
                'pincode' => $this->exhibitor['pincode'],
                'city' => $this->exhibitor['city'] ?? null,
                'state' => $this->exhibitor['state'] ?? null,
                'country' => $this->exhibitor['country'],
            ]);

            $currentProductIds = ExhibitorProduct::where('exhibitor_id', $this->exhibitorData->id)->pluck('product_id')->toArray();

            // Remove products
            $removedProductIds = array_diff($currentProductIds, $selectedProducts);

            if (count($removedProductIds) > 0) {

                foreach ($removedProductIds as $removedProductId) {

                    $productExists = ExhibitorProduct::where('exhibitor_id', $this->exhibitorData->id)
                        ->where('product_id', $removedProductId)
                        ->first();

                    if ($productExists) {
                        $productExists->delete();
                    }
                }
            }

            foreach ($selectedProducts as $productId) {

                $productExists = ExhibitorProduct::where('exhibitor_id', $this->exhibitorData->id)
                    ->where('product_id', $productId)
                    ->first();

                if (!$productExists) {
                    $this->exhibitorData->exhibitorProducts()->create([
                        'product_id' => $productId,
                    ]);
                }
            }

            $this->exhibitorData->update(['updated_by' => null]);

            DB::commit();
            session()->flash('success', 'Your profile updated successfully.');
            redirect()->route('exhibitor.profile');
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', $e->getMessage());
            return;
        }
    }
    public function getEventId($eventId)
    {
        $this->eventId = $eventId;
        $exhibitor = $this->exhibitorData->eventExhibitors()->where('event_id', $eventId)->first();
        $this->exhibitor_products = $exhibitor->products ?? '';
        $this->dispatch('showProducts', id: $this->exhibitor_products);
    }
    public function render()
    {
        $exhibitorProducts = ExhibitorProduct::where('exhibitor_id', $this->authId)->paginate($this->perPage);

        return view('livewire.exhibitor-profile', [
            'exhibitorProducts' => $exhibitorProducts,
        ])->layout('layouts.admin');
    }
}
