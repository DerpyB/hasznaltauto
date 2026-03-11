<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\AuthController;

// ==================== NYILVÁNOS ROUTOK ====================

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
Route::post('/resend-verification', [AuthController::class, 'resendVerification']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/validate-reset-token', [AuthController::class, 'validateResetToken']);

// Cars - listázás nyilvános
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/{id}', [CarController::class, 'show']);

// Parts - listázás nyilvános
Route::get('/parts', [PartController::class, 'index']);
Route::get('/parts/{id}', [PartController::class, 'show']);

// Listings - listázás nyilvános
Route::get('/listings', [ListingController::class, 'index']);
Route::get('/listings/{id}', [ListingController::class, 'show']);

// ==================== VÉDETT ROUTOK ====================

Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Cars - módosítás csak bejelentkezve
    Route::post('/cars', [CarController::class, 'store']);
    Route::put('/cars/{id}', [CarController::class, 'update']);
    Route::delete('/cars/{id}', [CarController::class, 'destroy']);
    
    // Parts - módosítás csak bejelentkezve
    Route::post('/parts', [PartController::class, 'store']);
    Route::put('/parts/{id}', [PartController::class, 'update']);
    Route::delete('/parts/{id}', [PartController::class, 'destroy']);
    
    // Listings - módosítás csak bejelentkezve
    Route::post('/listings', [ListingController::class, 'store']);
    Route::put('/listings/{id}', [ListingController::class, 'update']);
    Route::delete('/listings/{id}', [ListingController::class, 'destroy']);
});