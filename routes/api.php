<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// API for Register and Login
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// API for Products
Route::group(['prefix' => 'v1', 'as' => 'v1'], function () {
    Route::apiResource('/products', ProductController::class)->middleware('auth:sanctum');
});
