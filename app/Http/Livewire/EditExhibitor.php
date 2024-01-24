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

class EditExhibitor extends Component
{
    public $showPassword = false;
    public $categories = [];
    public $products = [];
    public $eventId;
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
        'registration_type' => 'web',
        'pincode',
        'city',
        'state',
        'country' => 'India',
        'address' => '',
    ];

    protected $rules = [
        'exhibitor.salutation' => 'required',
        'exhibitor.name' => 'required|regex:/^[a-zA-Z ]+$/',
        'exhibitor.designation' => 'required',
        'exhibitor.contact_number' => 'required|digits:10|regex:/^[0-9]{10}$/',
        'exhibitor.username' => 'required|string',
        'exhibitor.company_name' => 'required',
        'exhibitor.category_id' => 'required',
        'exhibitor.products' => 'required',
        'exhibitor.email' => 'required|email',
        'exhibitor.mobile_number' => 'required|regex:/^[0-9]*$/',
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
        'exhibitor.username.required' => 'Username is required',
        'exhibitor.company_name.required' => 'Company name is required',
        'exhibitor.category_id.required' => 'Business type is required',
        'exhibitor.products.required' => 'Products is required',
        'exhibitor.email.required' => 'Email is required',
        'exhibitor.email.email' => 'Enter valid email',
        'exhibitor.mobile_number.required' => 'Contact number is required',
        'exhibitor.mobile_number.regex' => 'Enter valid contact number',
        'exhibitor.country.required' => 'Country is required',
        'exhibitor.pincode.required' => 'Pincode/Zipcode is required',
        'exhibitor.address.required' => 'Address is required',

    ];

    public function mount($exhibitorId)
    {
        if ($exhibitorId) {
            $exhibitor = Exhibitor::find($exhibitorId);
            if ($exhibitor) {
                $this->exhibitor['id'] = $exhibitor->id;
                $this->exhibitor['salutation'] = $exhibitor->exhibitorContact->salutation ?? '';
                $this->exhibitor['name'] = $exhibitor->exhibitorContact->name ?? '';
                $this->exhibitor['designation'] = $exhibitor->exhibitorContact->designation ?? '';
                $this->exhibitor['contact_number'] = $exhibitor->exhibitorContact->contact_number ?? '';
                $this->exhibitor['username'] = $exhibitor->username;
                $this->exhibitor['company_name'] = $exhibitor->name;
                $this->exhibitor['category_id'] = $exhibitor->category->id ?? '';
                $this->exhibitor['email'] = $exhibitor->email;
                $this->exhibitor['mobile_number'] = $exhibitor->mobile_number;
                $this->exhibitor['pincode'] = $exhibitor->address->pincode ?? '';
                $this->exhibitor['city'] = $exhibitor->address->city ?? '';
                $this->exhibitor['state'] = $exhibitor->address->state ?? '';
                $this->exhibitor['country'] = ucwords(strtolower($exhibitor->address->country)) ?? '';
                $this->exhibitor['address'] = $exhibitor->address->address ?? '';
                $selectedEventProducts = $exhibitor->eventExhibitors->where('event_id', $this->eventId)->pluck('products')->flatten()->toArray();
                $selectedProducts = $exhibitor->exhibitorProducts->pluck('product_id');
                $this->exhibitor['products'] = isset($this->eventId) ? $selectedEventProducts : $selectedProducts ?? [];
            } else {
                return redirect()->back()->with('warning', 'Exhibitor not found');
            }
        }

        $this->categories = Category::where('type', 'exhibitor_business_type')
            ->where('is_active', 1)
            ->get();
        $this->products = Product::pluck('name', 'id');
        $this->countries = getCountries();
    }

    public function render()
    {
        return view('livewire.edit-exhibitor')->layout('layouts.admin');
    }



    public function update()
    {
        $this->validate();

        $exhibitorUsernameExists = Exhibitor::where('username', $this->exhibitor['username'])->where('id', '!=', $this->exhibitor['id'])->first();
        if ($exhibitorUsernameExists) {
            $this->addError('exhibitor.username', 'Username already exists');
            return;
        }
        $exhibitorEmailExists = Exhibitor::where('email', $this->exhibitor['email'])->where('id', '!=', $this->exhibitor['id'])->first();
        if ($exhibitorEmailExists) {
            $this->addError('exhibitor.email', 'Email already exists');
            return;
        }
        $exhibitorPhoneNoExists = Exhibitor::where('mobile_number', $this->exhibitor['mobile_number'])->where('id', '!=', $this->exhibitor['id'])->first();
        if ($exhibitorPhoneNoExists) {
            $this->addError('exhibitor.mobile_number', 'Phone number already exists');
            return;
        }
        $exhibitorContactNoExists = ExhibitorContact::where('contact_number', $this->exhibitor['contact_number'])->where('exhibitor_id', '!=', $this->exhibitor['id'])->first();
        if ($exhibitorContactNoExists) {
            $this->addError('exhibitor.contact_number', 'Contact number already exists');
            return;
        }

        $this->username_exists = false;
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
            $exhibitor = Exhibitor::findOrFail($this->exhibitor['id']);
            $exhibitor->update([
                'username' => $this->exhibitor['username'],
                'name' => $this->exhibitor['company_name'],
                'category_id' => $this->exhibitor['category_id'],
                'email' => $this->exhibitor['email'],
                'mobile_number' => $this->exhibitor['mobile_number'],
                'known_source' => $this->exhibitor['known_source'] ?? '',
                'registration_type' => $this->exhibitor['registration_type'],

            ]);


            $exhibitor->exhibitorContact()->update([
                'salutation' => $this->exhibitor['salutation'],
                'name' => $this->exhibitor['name'],
                'contact_number' => $this->exhibitor['contact_number'],
                'designation' => $this->exhibitor['designation'],
            ]);

            if ($this->eventId) {
                $exhibitor->eventExhibitors()->where('event_id', $this->eventId)->update([
                    'products' => $selectedProducts
                ]);
                foreach ($selectedProducts as $productId) {

                    $productExists = ExhibitorProduct::where('exhibitor_id', $exhibitor->id)
                        ->where('product_id', $productId)
                        ->first();

                    if (!$productExists) {
                        $exhibitor->exhibitorProducts()->create([
                            'product_id' => $productId,
                        ]);
                    }
                }
            }


            $exhibitor->address()->update([
                'address' => $this->exhibitor['address'],
                'pincode' => $this->exhibitor['pincode'],
                'city' => $this->exhibitor['city'] ?? null,
                'state' => $this->exhibitor['state'] ?? null,
                'country' => $this->exhibitor['country'],
            ]);

            if (!$this->eventId) {
                $currentProductIds = ExhibitorProduct::where('exhibitor_id', $exhibitor->id)->pluck('product_id')->toArray();

                // Remove products
                $removedProductIds = array_diff($currentProductIds, $selectedProducts);

                if (count($removedProductIds) > 0) {

                    foreach ($removedProductIds as $removedProductId) {

                        $productExists = ExhibitorProduct::where('exhibitor_id', $exhibitor->id)
                            ->where('product_id', $removedProductId)
                            ->first();

                        if ($productExists) {
                            $productExists->delete();
                        }
                    }
                }

                foreach ($selectedProducts as $productId) {

                    $productExists = ExhibitorProduct::where('exhibitor_id', $exhibitor->id)
                        ->where('product_id', $productId)
                        ->first();

                    if (!$productExists) {
                        $exhibitor->exhibitorProducts()->create([
                            'product_id' => $productId,
                        ]);
                    }
                }
            }

            $authData = getAuthData();
            if ($authData !== null) {
                $exhibitor->update(['updated_by' => $authData->id]);
            } else {
                $exhibitor->update(['updated_by' => null]);
            }

            DB::commit();
            session()->flash('success', 'Exhibitor updated successfully.');
            if (isset($this->eventId)) {
                return redirect(route('exhibitor.summary', ['eventId' => $this->eventId]));
            }
            return redirect(route('exhibitor.summary'));
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', $e->getMessage());
            return;
        }
    }

    public function clearLocationFields()
    {
        $this->exhibitor['city'] = null;
        $this->exhibitor['state'] = null;
        $this->exhibitor['pincode'] = null;
    }
    public function pincode()
    {
        if (strtolower($this->exhibitor['country']) == 'india' && isset($this->exhibitor['pincode'])) {
            $pincodeData = getPincodeData($this->exhibitor['pincode']);
            if ($pincodeData['state'] === null && $pincodeData['city'] === null) {
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
}
