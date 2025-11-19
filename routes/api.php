<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\GalleryController;
use App\Http\Controllers\API\EcomapController;
use App\Http\Controllers\API\DesaController;
use App\Http\Controllers\API\UserController;

// TEMPORARY: Test Desa tanpa auth
Route::post('/desas-test', [DesaController::class, 'store']);
Route::get('/desas-test', [DesaController::class, 'index']);

// Public Routes
Route::post('/login', [AuthController::class, 'login']);

Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/blogs/{id}', [BlogController::class, 'show']);

Route::get('/galleries', [GalleryController::class, 'index']);
Route::get('/galleries/{id}', [GalleryController::class, 'show']);

Route::get('/desas', [DesaController::class, 'index']);
Route::get('/desas/{id}', [DesaController::class, 'show']);

Route::get('/ecomaps', [EcomapController::class, 'index']);
Route::get('/ecomaps/{id}', [EcomapController::class, 'show']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::post('/blogs', [BlogController::class, 'store']);
    Route::post('/blogs/{id}', [BlogController::class, 'update']);
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);

    Route::post('/galleries', [GalleryController::class, 'store']);
    Route::post('/galleries/{id}', [GalleryController::class, 'update']);
    Route::delete('/galleries/{id}', [GalleryController::class, 'destroy']);

    Route::post('/desas', [DesaController::class, 'store']);
    Route::put('/desas/{id}', [DesaController::class, 'update']);
    Route::delete('/desas/{id}', [DesaController::class, 'destroy']);

    Route::post('/ecomaps', [EcomapController::class, 'store']);
    Route::put('/ecomaps/{id}', [EcomapController::class, 'update']);
    Route::delete('/ecomaps/{id}', [EcomapController::class, 'destroy']);
});

// Admin Only Routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // User Management
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/users/{id}/toggle-active', [UserController::class, 'toggleActive']);
    Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword']);
});
