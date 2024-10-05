<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PlaceOrderRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Order;
use App\Models\Product;

class PlaceOrderController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [new Middleware('auth:sanctum')];
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(PlaceOrderRequest $request)
    {

        $productIds = collect($request->input('products'))->pluck('id');
        $productsData = Product::whereIn('id', $productIds)->get(['id', 'quantity']);

        if ($productsData->count() !== $productIds->count()) {
            return response()->json(['error' => 'Some product IDs do not exist.'], 422);
        }

        $productsRequestData = $request->input('products');

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => Auth::user()->id,
            ]);

            $attachData = [];

            for ($i = 0; $i < $productsData->count(); $i++) {
                $productId = $productsData[$i]['id'];
                $stock = $productsData[$i]['quantity'];
                $quantityRequired = $productsRequestData[$i]['quantity'];
                if ($stock < $productsRequestData[$i]['quantity']) {
                    DB::rollBack();
                    return response()->json(['error' => 'Not enough items of product with Id ' . $productId . ' in stock'], 422);
                }
                $productsData[$i]->decrement('quantity', $quantityRequired);
                $attachData[$productId] = ['quantity' => $quantityRequired];
            }

            $order->products()->attach($attachData);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            error_log($e->getMessage());
            return response()->json(["error" => 'Server Error'], 500);
        }

        return response()->json($order->load('products'), 201);
    }
}
