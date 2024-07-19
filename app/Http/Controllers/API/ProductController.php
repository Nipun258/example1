<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();

        if ($products->count() > 0) {

            return ProductResource::collection($products);

        } else {

            return response()->json([
                'message' => 'No products found'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|nullable',
            'price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All the Feild are Mandatory',
                'data' => $validator->errors()
            ], 400);
        }

        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource(Product::latest()->first())

        ], 200);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(Product $product, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string|nullable',
            'price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All the Feild are Mandatory',
                'data' => $validator->errors()
            ], 400);
        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => new ProductResource($product)

        ], 200);

    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
