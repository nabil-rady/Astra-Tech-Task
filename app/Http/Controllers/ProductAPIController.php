<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class ProductAPIController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [new Middleware('auth:sanctum', only: ['store', 'update', 'delete']), new Middleware(AdminMiddleware::class, only: ['store', 'update', 'destroy'])];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:10|max:255',
            'image' => 'required|url',
            'price' => 'required|numeric|gt:0',
            'quantity' => 'required|integer|gte:0',
            'category_id' => 'required|integer|exists:categories,id'
        ]);

        $product = Product::create($data);

        return $product;
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title' => 'string|min:3|max:255',
            'description' => 'string|min:10|max:255',
            'image' => 'url',
            'price' => 'numeric|gt:0',
            'quantity' => 'integer|gte:0',
            'category_id' => 'integer|exists:categories,id'
        ]);

        $product->update($data);

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([], 204);
    }
}
