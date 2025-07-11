<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'product_desc' => 'required|string|max:255',
            'product_price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = time() . '.' . Str::random(10) . '.' . $extension;
            $image->storeAs('images', $imageName, 'public');
            $data['image'] = 'images/' . $imageName;
        }

        // add user id to the data
        $data['user_id'] = $request->user()->id;

        $product = Product::create($data);

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product created successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'nullable|string|max:255',
            'product_desc' => 'nullable|string|max:255',
            'product_price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'messages' => $validator->errors()
            ], 422);
        }

        $product = Product::find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $data = $validator->validated();

        // if new image is uploaded, delete the old image
        if ($request->hasFile('image')) {
            // delete the old image
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // store the new image
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imageName = time() . '.' . Str::random(10) . '.' . $extension;
            $image->storeAs('images', $imageName, 'public');
            $data['image'] = 'images/' . $imageName;
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(null, 204);
        }

        // delete image from storage if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
