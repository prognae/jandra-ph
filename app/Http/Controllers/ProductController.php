<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request) {
        $validated = $request->validate([
            'page' => ['integer', 'min:1'],
            'limit' => ['integer', 'min:10', 'max:100'],
            'order' => ['string', 'in:asc,desc'],
            'sort' => ['string', 'in:id,name'],
            'search' => ['string', 'max:255'],
            'category' => ['integer']
        ]);

        $limit = $validated['limit'] ?? 10;

        $order = $validated['order'] ?? 'desc';

        $sort = $validated['sort'] ?? 'id';

        $products = Product::with('category');

        if(isset($validated['category'])) {
            $products->where('category', $validated['category']);
        }

        if(isset($validated['search'])) {
            $products->where('product_name', 'like', '%'. $validated['search'] .'%');
        }

        if($products->doesntExist()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No products found.'
            ]);
        }

        $data = $products->orderBy($sort, $order)->paginate($limit);

        return response()->json([
            'status' => 'success',
            'message' => 'Products retrieved successfully',
            'data' => $data
        ]);
    }

    public function show($id) {
        $product = Product::with('category')->where('id', $id);

        if($product->doesntExist()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No product found.'
            ]);
        }

        $data = $product->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Product retrieved.',
            'data' => $data
        ]);
    }

    public function store(Request $request) {
        $count = Category::count();

        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'integer', 'min:1', 'max:' . $count],
            'price' => ['required', 'numeric', 'min:1'],
            'shopee_url' => ['url'],
            'main_description' => ['required', 'string', 'max:500'],
            'product_information' => ['required', 'string', 'max:500'],
            'material_used' => ['required', 'string', 'max:500'],
            'main_photo' => ['required', 'url'],
            'thumbnail_main_photo' => ['required', 'url']
        ]);

        $product = new Product();

        $product->product_name = $validated['product_name'];

        $product->category = $validated['category'];

        $product->price = $validated['price'];

        $product->shopee_url = $validated['shopee_url'];

        $product->main_description = $validated['main_description'];

        $product->product_information = $validated['product_information'];

        $product->material_used = $validated['material_used'];

        $product->main_photo = $validated['main_photo'];

        $product->thumbnail_main_photo = $validated['thumbnail_main_photo'];

        $product->save();

        if(!$product->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error on product creation.'
            ]);
        } else if($product->id) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully.'
            ]);
        }
    }

    public function update(Request $request, $id) {
        $count = Category::count();

        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'integer', 'min:1', 'max:' . $count],
            'price' => ['required', 'numeric', 'min:1'],
            'shopee_url' => ['url'],
            'main_description' => ['required', 'string', 'max:500'],
            'product_information' => ['required', 'string', 'max:500'],
            'material_used' => ['required', 'string', 'max:500'],
            'main_photo' => ['required', 'url'],
            'thumbnail_main_photo' => ['required', 'url']
        ]);

        $product = Product::find($id);

        if(!isset($product)) {
            return response()->json([
                'status' => 'error',
                'message' => 'No product found.'
            ]);
        }

        $product->product_name = $validated['product_name'];

        $product->category = $validated['category'];

        $product->price = $validated['price'];

        $product->shopee_url = $validated['shopee_url'];

        $product->main_description = $validated['main_description'];

        $product->product_information = $validated['product_information'];

        $product->material_used = $validated['material_used'];

        $product->main_photo = $validated['main_photo'];

        $product->thumbnail_main_photo = $validated['thumbnail_main_photo'];

        if(!$product->isDirty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No changes has been made.'
            ]);
        } else if($product->isDirty()) {
            $product->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Product updated successfully.'
            ]);
        }
    }

    public function delete($id) {
        $product = Product::find($id);

        if(!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'No product found.'
            ]);
        }

        $delete = $product->delete();

        if(!$delete) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database error.'
            ]);
        } else if($delete) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product has been deleted.'
            ]);
        }
    }

    public function restore($id) {
        $product = Product::where('id', $id)->onlyTrashed()->first();

        if(!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'No product found.'
            ]);
        }

        $restore = $product->restore();

        if(!$restore) {
            return response()->json([
                'status' => 'error',
                'message' => 'Database error.'
            ]);
        } else if($restore) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product has been restored.'
            ]);
        }
    }
}
