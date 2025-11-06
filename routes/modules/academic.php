<?php

use App\Http\Controllers\Academic\ClassController;
use Illuminate\Support\Facades\Route;

Route::prefix('classes')->name('classes.')->group(function () {
    Route::get('/', [ClassController::class, 'index'])->name('index');
    Route::get('/create', [ClassController::class, 'create'])->name('create');
    Route::post('/', [ClassController::class, 'store'])->name('store');
    Route::get('/{class}', [ClassController::class, 'show'])->name('show');
    Route::get('/{class}/edit', [ClassController::class, 'edit'])->name('edit');
    Route::put('/{class}', [ClassController::class, 'update'])->name('update');
    Route::delete('/{class}', [ClassController::class, 'destroy'])->name('destroy');
    
    // Student Management
    Route::get('/{class}/students', [ClassController::class, 'manageStudents'])->name('students.manage');
    Route::post('/{class}/students/assign', [ClassController::class, 'assignStudent'])->name('students.assign');
});

// Additional academic routes can be added here
Route::prefix('academic')->name('academic.')->group(function () {
    // Future routes for grades, majors, sections management
});