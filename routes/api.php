<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductApiController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('products', ProductApiController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('products', [ProductApiController::class, 'index']);
    Route::get('products/{id}', [ProductApiController::class, 'show']);
    Route::post('products', [ProductApiController::class, 'store']);
    Route::put('products/{id}', [ProductApiController::class, 'update']);
    Route::delete('products/{id}', [ProductApiController::class, 'destroy']);
    Route::post('logout', [AuthController::class, 'logout']);
});
