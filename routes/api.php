<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CartsController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Models\UsersProfile;
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
Route::post('register', [AuthenticationController::class, 'register']);
Route::get('login/google', [AuthenticationController::class, 'googleRedirect']);
Route::get('login/google/callback', [AuthenticationController::class, 'loginGoogle']);

// Product
Route::get('product', [ProductsController::class, 'index']);
Route::get('product/{id}', [ProductsController::class, 'show']);
Route::post('product', [ProductsController::class, 'store'])->middleware('auth.jwt');
Route::put('product/{id}', [ProductsController::class, 'update'])->middleware('auth.jwt');
Route::delete('product/{id}', [ProductsController::class, 'destroy'])->middleware('auth.jwt');

// Category
// Route::apiResource('category', CategoriesController::class);
Route::get('category', [CategoriesController::class, 'index']);
Route::get('category/{id}', [CategoriesController::class, 'show']);
Route::post('category', [CategoriesController::class, 'store'])->middleware('auth.jwt');
Route::put('category/{id}', [CategoriesController::class, 'update'])->middleware('auth.jwt');
Route::delete('category/{id}', [CategoriesController::class, 'destroy'])->middleware('auth.jwt');

// Carts
Route::get('cart/{id}', [CartsController::class, 'show'])->middleware('auth.jwt');
Route::post('cart', [CartsController::class, 'store'])->middleware('auth.jwt');
Route::put('cart/{id}', [CartsController::class, 'update'])->middleware('auth.jwt');
Route::delete('cart/{id}', [CartsController::class, 'destroy'])->middleware('auth.jwt');

// Orders
Route::get('order/{id}', [OrdersController::class, 'show'])->middleware('auth.jwt');
Route::post('order', [OrdersController::class, 'store'])->middleware('auth.jwt');

//Account
Route::get('account/{id}', [ProfileController::class, 'show'])->middleware('auth.jwt');
Route::put('account/{id}', [ProfileController::class, 'update'])->middleware('auth.jwt');
Route::put('account/password/{id}', [ProfileController::class, 'updatePassword'])->middleware('auth.jwt');