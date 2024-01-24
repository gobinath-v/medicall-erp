<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\ExhibitorProduct;

class ProductHandler extends Component
{
    use WithFileUploads;

    public $photo;

    public $productId = null;

    public $product = [
        'name',
        'description',
        'tags' => '',
        'category_id',
    ];

    protected $rules = [
        'product.name' => 'required|string',
        'product.category_id' => 'required',
    ];

    protected $messages = [
        'product.name.required' => 'Name is required',
        'product.category_id.required' => 'Category is required',
    ];

    public function create()
    {
        $this->validate();

        $productNameExists = Product::where('name', $this->product['name'])->first();
        if ($productNameExists) {
            $this->addError("product.name", "Name already exists");
            return;
        }

        try {

            if (auth()->guard('exhibitor')->check()) {

                $this->product['created_by'] = null;
                $this->product['updated_by'] = null;
            } else {

                $this->product['created_by'] = getAuthData()->id;
                $this->product['updated_by'] = getAuthData()->id;
            }

            $images =  [];
            // $images = array_values($images);
            if ($this->photo) {
                $imageFolderPath = 'products/' . date('Y/m');
                foreach ($this->photo as $photo) {
                    $imageName = $photo->getClientOriginalName();
                    $filePath = $photo->storeAs($imageFolderPath, $imageName, 'public');
                    $images[] = [
                        'id'  => Str::random(10),
                        'filePath'  => $filePath
                    ];
                }
            }
            // $imageData = [];
            // foreach ($imagePaths as $path) {
            //     $imageData[] = [
            //         "id" => Str::random(10),
            //         "filePath" => $path,
            //     ];
            // }

            $product = Product::create([
                'name' => $this->product['name'],
                'description' => $this->product['description'] ?? null,
                'tags' => $this->product['tags'],
                'category_id' => $this->product['category_id'],
                // 'image' => json_encode($imagePaths),
                '_meta' => [
                    'images' => $images,
                ]
            ]);

            if ($product) {

                if (auth()->guard('exhibitor')->check()) {
                    $exibitorProduct = ExhibitorProduct::create([
                        'exhibitor_id' => auth()->guard('exhibitor')->user()->id,
                        'product_id' => $product->id,
                    ]);
                }

                session()->flash('success', 'Product Successfully Created');
                if (auth()->guard('exhibitor')->check()) {
                    return redirect(route('myproducts'));
                }
                return redirect(route('products'));
            }
            session()->flash('error', 'Error while creating a Product');
            return;
        } catch (\Exception $e) {
            session()->flash("error", $e->getMessage());
            return;
        }
    }

    public function update()
    {
        $this->validate();
        $productNameExists = Product::where('name', $this->product['name'])
            ->where('id', '!=', $this->productId)->first();

        if ($productNameExists) {
            $this->addError("product.name", "Name already exists");
            return;
        }

        try {

            $product = Product::find($this->productId);

            if (auth()->guard('exhibitor')->check()) {
                $this->product['updated_by'] = null;
            } else {
                $this->product['updated_by'] = getAuthData()->id;
            }

            $images = $this->product['_meta']['images'] ?? [];
            $images = array_values($images);
            if ($this->photo !== null) {

                $imageFolderPath = 'products/' . date('Y/m');
                foreach ($this->photo as $photo) {
                    $imageName = $photo->getClientOriginalName();
                    $filePath = $photo->storeAs($imageFolderPath, $imageName, 'public');
                    $images[] = [
                        'id'  => Str::random(10),
                        'filePath'  => $filePath
                    ];
                    // $this->product['image'] = $imagePath;
                }
            }


            if ($product) {
                $product->update([
                    'name' => $this->product['name'],
                    'description' => $this->product['description'] ?? null,
                    'tags' => $this->product['tags'],
                    'category_id' => $this->product['category_id'],
                    // 'image' => $this->product['image'],
                    '_meta' => [
                        'images' => $images,
                    ]
                ]);

                $isUpdated = $product->wasChanged('name', 'description', 'tags', 'image', '_meta', 'category_id');

                if ($isUpdated) {
                    session()->flash("success", "Product Details Updated Successfully");

                    if (auth()->guard('exhibitor')->check()) {

                        return redirect(route("myproducts"));
                    } else {
                        return redirect(route("products"));
                    }
                } else {
                    session()->flash("error", "Do Some Modification to be Update Product Details");
                    return;
                }
            }
            session()->flash("error", "Unable to Update Product Details");
            return;
        } catch (\Exception $e) {
            session()->flash("error", $e->getMessage());
            return;
        }
    }

    public function deleteImg($productImageId)
    {
        $productImages = $this->product['_meta']['images'] ?? [];
        if (empty($productImages)) {
            return;
        }

        foreach ($productImages as $index => $productImageMeta) {

            if ($productImageMeta['id'] != $productImageId) {
                continue;
            }

            $filepath = public_path('storage/' . $productImageMeta['filePath']);
            if (file_exists($filepath)) {
                unlink($filepath);
                unset($productImages[$index]);
            }
        }
        $meta = $this->product['_meta'];
        $meta['images'] = array_values($productImages);

        $product = Product::where('id', $this->product['id'])->first();
        $product->_meta = $meta;
        $product->save();
        if ($product) {
            session()->flash('success', 'Product image deleted successfully');
            return redirect()->route('products', ['productId' => $product->id]);
        }
        session()->flash('error', 'Something went wrong.');
    }

    public function resetFields()
    {
        $this->reset();
    }

    public function mount($productId = null)
    {
        $this->productId = $productId;
        if ($this->productId) {
            $product = Product::find($this->productId);
            if ($product) {
                $this->product = $product->toArray();
            }

            // $product ? $productDetails = $product->toArray() : [];
            // if (!(empty($productDetails))) {
            //     $this->product['name'] = $productDetails['name'];
            //     // $this->product['image'] = $productDetails['image'];
            //     $this->product['_meta'] = $productDetails['_meta'];
            //     $this->product['category_id'] = $productDetails['category_id'];
            //     $this->product['tags'] = $productDetails['tags'];
            //     $this->product['description'] = $productDetails['description'];
            // }
            return;
        }
    }

    public function render()
    {
        $categories = Category::where('is_active', 1)->where('type', 'product_type')->get();
        $tags = Category::where('is_active', 1)->where('type', 'product_tags')->get();
        return view('livewire.product-handler', [
            'categories' => $categories,
            'tags' => $tags,
        ]);
    }
}
