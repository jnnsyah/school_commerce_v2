<?php

use App\Http\Controllers\Inventory\StockController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory')->name('inventory.')->middleware('can:product.manage')->group(function () {
    // Stock Overview
    Route::get('/', [StockController::class, 'index'])->name('index');
    
    // Variant Stock Management
    Route::get('/variants', [StockController::class, 'variants'])->name('variants');
    Route::post('/variants/{variant}/adjust', [StockController::class, 'adjustVariantStock'])->name('variants.adjust');
    
    // Extras Stock Management
    Route::get('/extras', [StockController::class, 'extras'])->name('extras');
    Route::post('/extras/{extra}/adjust', [StockController::class, 'adjustExtraStock'])->name('extras.adjust');
    
    // Stock Adjustments
    Route::get('/adjustments', [StockController::class, 'adjustments'])->name('adjustments');
    Route::post('/adjust-stock', [StockController::class, 'adjustStock'])->name('adjust-stock');
    
    // Stock Movement History
    Route::get('/movement-history', [StockController::class, 'movementHistory'])->name('movement-history');
});