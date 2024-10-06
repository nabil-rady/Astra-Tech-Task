<?php

use App\Http\Controllers\AuthAPIController;
use App\Http\Controllers\CategoryAPIController;
use App\Http\Controllers\PlaceOrderController;
use App\Http\Controllers\ProductAPIController;
use App\Http\Controllers\UserOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthAPIController::class, 'register']);
Route::post('login', [AuthAPIController::class, 'login']);

Route::post('order', PlaceOrderController::class);
Route::get('my-orders', UserOrderController::class);

Route::apiResource('category', CategoryAPIController::class);
Route::apiResource('product', ProductAPIController::class);
