<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// BASIC REST API routes example sa products

Route::prefix('products')->group(function () {


// GET /api/products - Get all products
// similar to select * 
Route::get('/', [ProductController::class, 'index']);

// GET /api/products/{id}
// Get single product
Route::get('/{id}', [ProductController:: class, 'show']);

// POST /api/products
// Create new products
Route::post('/', [ProductController:: class, 'store']);

// PUT /api/products/{id}
// Update product
Route::put('/{id}', [ProductController:: class, 'update']);

// DELETE /api/products/{id}
// Delete product
Route::delete('/{id}', [ProductController:: class, 'destroy']);


// Shortcut daw sya
// Route::apiResource('products', ProductController::class);

});