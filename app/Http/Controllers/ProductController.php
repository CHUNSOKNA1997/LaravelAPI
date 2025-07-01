<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all products
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = auth('sanctum')->user();
        $products = Product::where('user_id', $user->id)->get();
        return response()->json([
            'message' => 'Products retrieved successfully',
            'products' => ProductResource::collection($products),
        ]);
    }

    /**
     * Store a new product
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $user = auth('sanctum')->user();

        $fields = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required'],
            'quantity' => ['required', 'integer']
        ]);
        
        $product = Product::create([
            'name' => $fields['name'],
            'description' => $fields['description'],
            'price' => $fields['price'],
            'quantity' => $fields['quantity'],
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => ProductResource::make($product),
        ]);
    }

    /**
     * Show a specific product
     * @param string $uuid
     * @return JsonResponse
     */
    public function show(string $uuid): JsonResponse
    {
        $product = Product::where('uuid', $uuid)->first();
        $user = auth('sanctum')->user();

        if (!$product || $product->user_id !== $user->id) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json([
            'message' => 'Product retrieved successfully',
            'product' => ProductResource::make($product),
        ]);
    }

    /**
     * Update a product
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Request $request, string $uuid): JsonResponse
    {
        $product = Product::where('uuid', $uuid)->first();
        $user = auth('sanctum')->user();
        
        if (!$product || $product->user_id !== $user->id) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        
        $fields = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0']
        ]);
        
        $product->update($fields);
        
        return response()->json([
            'message' => 'Product updated successfully',
            'product' => ProductResource::make($product),
        ]);
    }

    /**
     * Delete a product
     * @param Product $product
     * @param string $uuid
     * @return JsonResponse
     */
    public function destroy(string $uuid): JsonResponse
    {
        $product = Product::where('uuid', $uuid)->first();
        $user = auth('sanctum')->user();

        if (!$product || $product->user_id !== $user->id) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
