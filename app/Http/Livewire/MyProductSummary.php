<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\ExhibitorProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class MyProductSummary extends Component
{
    use WithPagination;

    public $paginationTheme = 'bootstrap';

   
    public $productId = null;

    public $perPage = 10;

    public function mount(Request $request)
    {
        $this->productId = $request->productId;
    }

    public function render()
    {
        $products = [];

        if (auth()->guard('exhibitor')->check()) {
            $exhibitorId = auth()->guard('exhibitor')->user()->id;
            $exhibitorProducts = ExhibitorProduct::where('exhibitor_id', $exhibitorId)->pluck('product_id');

            if ($exhibitorProducts->count() > 0) {
                $products = Product::whereIn('id', $exhibitorProducts)->orderBy('id', 'desc')->paginate($this->perPage);
            }

            $productTags = [];

            foreach ($products as $product) {
                if (!empty($product->tags)) {
                    $tagNames = Category::where('type', 'product_tags')->whereIn('id', $product->tags)->pluck('name');
                    if (isset($tagNames) && count($tagNames) > 0) {
                        $productTags[$product->id] = $tagNames->toArray();
                    }
                }
            }

            // dd($products, auth()->guard('exhibitor')->user()->name);

            return view('livewire.my-product-summary', [
                'products' => $products,
                'productTags' => $productTags,
            ])->layout('layouts.admin');
        }
    }

}
