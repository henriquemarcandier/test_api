<?php

use App\Http\Controllers\LoginWebController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WebPaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [LoginWebController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginWebController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [LoginWebController::class, 'dashboard']);
    Route::get('/logout', [LoginWebController::class, 'logout']);
    Route::post('/convert', [LoginWebController::class, 'convert']);
    Route::get('/users', [UsersController::class, 'index']);
    Route::post('/users', [UsersController::class, 'store'])->middleware('can:finance');
    Route::put('/users/{user}', [UsersController::class, 'update']);
    Route::delete('/users/{user}', [UsersController::class, 'destroy']);

    Route::get('/payment', [WebPaymentController::class, 'index']);
    Route::post('/payment', [WebPaymentController::class, 'store'])->middleware('can:finance');
    Route::put('/payment/{payment}', [WebPaymentController::class, 'update']);
    Route::delete('/payment/{payment}', [WebPaymentController::class, 'destroy']);
});

Route::get('/', function () {
    return redirect('/login');
});
