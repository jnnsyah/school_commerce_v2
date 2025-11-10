<?php

use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
    Route::get('/products', [ReportController::class, 'products'])->name('products');
    Route::get('/students', [ReportController::class, 'students'])->name('students');
    Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
});