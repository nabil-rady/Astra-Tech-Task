<?php

use App\Http\Controllers\AuthAPIController;
use App\Http\Controllers\CategoryAPIController;
use App\Http\Controllers\PlaceOrderController;
use App\Http\Controllers\ProductAPIController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('register', [AuthAPIController::class, 'register']);
Route::post('login', [AuthAPIController::class, 'login']);

Route::post('order', [PlaceOrderController::class, '__invoke']);

Route::apiResource('category', CategoryAPIController::class);
Route::apiResource('product', ProductAPIController::class);
