<?php

use App\Http\Controllers\Inventory\StockController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory')->name('inventory.')->middleware('permission:product.manage')->group(function () {
    
    // Stock Management
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::post('/{product}/adjust', [StockController::class, 'adjustStock'])->name('adjust');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/movement-history', [StockController::class, 'movementHistory'])->name('movement');
        Route::get('/stock-levels', [StockController::class, 'stockLevelReport'])->name('stock-level');
    });
});