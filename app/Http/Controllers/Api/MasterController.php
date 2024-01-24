<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class MasterController extends Controller
{
    public function showProducts(Request $request)
    {
        $search = $request->search;
        if (!$search) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Please enter search key'
            ], 404);
        }
        try {
            $products = Product::where('name', 'like', '%' . $search . '%')->get();
            if ($products->count() == 0) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'No product found'
                ], 404);
            }
            $data = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                ];
            });
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ], 404);

        }
    }
    public function showCategories(Request $request)
    {
        $search = $request->search;
        if (!$search) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Please enter search key'
            ], 404);
        }
        try {
            $categories = Category::where('type', 'exhibitor_business_type')
                ->where('name', 'like', '%' . $search . '%')->get();

            if ($categories->count() == 0) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'No category found'
                ], 404);
            }
            $data = $categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
            });
            return response()->json([
                'status' => 'success',
                'data' => $data,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ], 404);

        }
    }

    public function addProducts(Request $request)
    {
        $newProduct = $request->product_name;
        if (!$newProduct) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Please enter new product'
            ], 404);
        }
        $checkProduct = Product::where('name', $newProduct)->first();
        if ($checkProduct) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Product already exists'
            ], 404);
        }
        try {
            $product = new Product();
            $product->name = $newProduct;
            $product->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Product added successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'fail',
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
