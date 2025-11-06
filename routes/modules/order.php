<?php

use App\Http\Controllers\Order\CartController;
use App\Http\Controllers\Order\OrderController;
use Illuminate\Support\Facades\Route;

// Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'addItem'])->name('add');
    Route::put('/items/{cartItem}', [CartController::class, 'updateItem'])->name('update');
    Route::delete('/items/{cartItem}', [CartController::class, 'removeItem'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
});

// Order Routes
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('index');
    Route::get('/create', [OrderController::class, 'create'])->name('create');
    Route::post('/', [OrderController::class, 'store'])->name('store');
    Route::get('/{order}', [OrderController::class, 'show'])->name('show');
    Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('update-status');
    Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
});