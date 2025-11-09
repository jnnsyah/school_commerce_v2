<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

// Authentication Routes (Laravel Breeze)
require __DIR__.'/auth.php';

// Public Routes
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Module Routes
    require __DIR__.'/modules/user.php';
    require __DIR__.'/modules/academic.php';
    require __DIR__.'/modules/product.php';
    require __DIR__.'/modules/inventory.php';
    require __DIR__.'/modules/payment.php';
    require __DIR__.'/modules/order.php';
    require __DIR__.'/modules/report.php';
});

// Fallback Route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});