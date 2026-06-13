<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentRequestController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    Route::apiResource('payment-requests', PaymentRequestController::class)
        ->only(['index', 'store', 'show']);

    Route::match(['get', 'patch'], '/payment-requests/{payment_request}/approve', [PaymentRequestController::class, 'approve']);
    Route::match(['get', 'patch'], '/payment-requests/{payment_request}/reject', [PaymentRequestController::class, 'reject']);
});
