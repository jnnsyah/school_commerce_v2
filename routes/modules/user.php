<?php
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ClassManagementController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

// User Management Routes
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserManagementController::class, 'index'])->name('index');
    Route::get('/create', [UserManagementController::class, 'create'])->name('create');
    Route::post('/', [UserManagementController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    Route::get('/students', [UserManagementController::class, 'students'])->name('students');
    Route::get('/teachers', [UserManagementController::class, 'teachers'])->name('teachers');
});

// Class Management Routes
Route::prefix('classes')->name('classes.')->group(function () {
    Route::get('/', [ClassManagementController::class, 'index'])->name('index');
    Route::get('/create', [ClassManagementController::class, 'create'])->name('create');
    Route::post('/', [ClassManagementController::class, 'store'])->name('store');
    Route::get('/{class}/edit', [ClassManagementController::class, 'edit'])->name('edit');
    Route::put('/{class}', [ClassManagementController::class, 'update'])->name('update');
    Route::delete('/{class}', [ClassManagementController::class, 'destroy'])->name('destroy');
    Route::post('/{class}/assign-teacher', [ClassManagementController::class, 'assignTeacher'])->name('assign-teacher');
});

// Report Routes
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
    Route::get('/products', [ReportController::class, 'products'])->name('products');
    Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
    Route::get('/students', [ReportController::class, 'students'])->name('students');
    Route::post('/export-sales', [ReportController::class, 'exportSales'])->name('export-sales');
    Route::post('/export-products', [ReportController::class, 'exportProducts'])->name('export-products');
});