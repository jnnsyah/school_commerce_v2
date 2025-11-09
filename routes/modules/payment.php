<?php
use App\Http\Controllers\Payment\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('payments')->name('payments.')->group(function () {
    // Payment checkout
    Route::get('/order/{order}/checkout', [PaymentController::class, 'create'])->name('checkout');
    Route::get('/order/{order}/success', [PaymentController::class, 'success'])->name('success');
    Route::get('/order/{order}/failure', [PaymentController::class, 'failure'])->name('failure');
    
    // Payment status check
    Route::get('/order/{order}/status', [PaymentController::class, 'checkStatus'])->name('status');
    
    // Midtrans callback (must be public)
    Route::post('/callback', [PaymentController::class, 'callback'])->name('callback');
});