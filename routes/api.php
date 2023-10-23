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
Route::apiResource('product', ProductsController::class);

// Category
Route::apiResource('category', CategoriesController::class);
