<?php

use App\Http\Controllers\Class\ClassController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasRole('super_admin') || $user->hasRole('admin')) {
        return redirect()->route('admin.users.index');
    }
    
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// Auth Routes (Breeze)
require __DIR__.'/auth.php';

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes dengan middleware yang benar
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Hanya super_admin dan admin yang bisa akses
    Route::middleware(['role:super_admin,admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
});

// Class Management Routes
Route::middleware(['auth'])->prefix('classes')->name('classes.')->group(function () {
    Route::get('/', [ClassController::class, 'index'])->name('index')->middleware('can:class.view');
    Route::get('/create', [ClassController::class, 'create'])->name('create')->middleware('can:class.create');
    Route::post('/', [ClassController::class, 'store'])->name('store')->middleware('can:class.create');
    Route::get('/{class}', [ClassController::class, 'show'])->name('show')->middleware('can:class.view');
    Route::get('/{class}/edit', [ClassController::class, 'edit'])->name('edit')->middleware('can:class.edit');
    Route::put('/{class}', [ClassController::class, 'update'])->name('update')->middleware('can:class.edit');
    Route::delete('/{class}', [ClassController::class, 'destroy'])->name('destroy')->middleware('can:class.delete');
    
    // Student Management
    Route::get('/{class}/students', [ClassController::class, 'manageStudents'])->name('manage-students')->middleware('can:class.edit');
    Route::put('/{class}/students', [ClassController::class, 'updateStudents'])->name('update-students')->middleware('can:class.edit');
});