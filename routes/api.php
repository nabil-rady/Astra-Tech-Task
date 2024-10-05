<?php

use App\Http\Controllers\CategoryAPIController;
use App\Http\Controllers\ProductAPIController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('category', CategoryAPIController::class);
Route::apiResource('product', ProductAPIController::class);
