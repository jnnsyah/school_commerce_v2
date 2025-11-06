<?php

use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ApprovalController;
use App\Http\Controllers\Product\CategoryController;
use Illuminate\Support\Facades\Route;

// Product CRUD Routes
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
});

// Product Approval Routes
Route::prefix('product-approval')->name('products.approval.')->middleware('permission:product.approve')->group(function () {
    Route::get('/', [ApprovalController::class, 'index'])->name('index');
    Route::post('/{product}/approve', [ApprovalController::class, 'approve'])->name('approve');
    Route::post('/{product}/reject', [ApprovalController::class, 'reject'])->name('reject');
});

// Product Category Routes
Route::prefix('product-categories')->name('products.categories.')->middleware('permission:product.manage')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
});