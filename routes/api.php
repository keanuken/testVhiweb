<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductApiController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// only admin can CRUD
Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('products', [ProductApiController::class, 'store']);
    Route::put('products/{id}', [ProductApiController::class, 'update']);
    Route::patch('products/{id}', [ProductApiController::class, 'update']);
    Route::delete('products/{id}', [ProductApiController::class, 'destroy']);
});

// user role can only get products
Route::middleware('auth:sanctum')->group(function () {
    Route::get('products', [ProductApiController::class, 'index']);
    Route::get('products/{id}', [ProductApiController::class, 'show']);
});
