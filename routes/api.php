<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function() {
    return 'API is working!';
});

// Test Authentication
Route::get('/rahasia', function() {
    return 'Ini pesan rahasia: !#$&(!$^';
})->middleware('auth.jwt');

// Authentication
Route::post('login', [AuthenticationController::class, 'login']);

// Product
Route::get('product', [ProductsController::class, 'index']);
Route::get('product/{id}', [ProductsController::class, 'show']);
Route::post('product', [ProductsController::class, 'store'])->middleware('auth.jwt');
Route::put('product/{id}', [ProductsController::class, 'update'])->middleware('auth.jwt');
Route::delete('product/{id}', [ProductsController::class, 'destroy'])->middleware('auth.jwt');

// Category
Route::apiResource('category', CategoriesController::class);
