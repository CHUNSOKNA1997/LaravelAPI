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
}
