<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;

// Authentication Routes (tetap pakai Breeze)
require __DIR__.'/auth.php';

// Public Route - Redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // DASHBOARD & PROFILE (untuk semua user)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/user/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // USER ROUTES (untuk student & guru_biasa)
    Route::prefix('user')->name('user.')->group(function () {
        // Products
        Route::get('/products', [App\Http\Controllers\Product\ProductController::class, 'userIndex'])->name('products.index');
        Route::get('/products/{product}', [App\Http\Controllers\Product\ProductController::class, 'userShow'])->name('products.show');
        
        // Orders
        Route::get('/orders', [App\Http\Controllers\Order\OrderController::class, 'userIndex'])->name('orders.index');
        Route::get('/orders/{order}', [App\Http\Controllers\Order\OrderController::class, 'userShow'])->name('orders.show');
        Route::post('/orders', [App\Http\Controllers\Order\OrderController::class, 'store'])->name('orders.store');
        
        // Cart
        Route::get('/cart', [App\Http\Controllers\Order\CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [App\Http\Controllers\Order\CartController::class, 'addItem'])->name('cart.add');
        Route::put('/cart/items/{cartItem}', [App\Http\Controllers\Order\CartController::class, 'updateItem'])->name('cart.update');
        Route::delete('/cart/items/{cartItem}', [App\Http\Controllers\Order\CartController::class, 'removeItem'])->name('cart.remove');
        Route::post('/cart/clear', [App\Http\Controllers\Order\CartController::class, 'clear'])->name('cart.clear');
    });

    // ADMIN/MERCHANT ROUTES (untuk admin, guru_pkwu, wali_kelas)
    Route::prefix('admin')->name('admin.')->middleware('can:access.merchant')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');
        
        // Products Management
        // Route::get('/products', [App\Http\Controllers\Product\ProductController::class, 'adminIndex'])->name('products.index');
        // Route::get('/products/create', [App\Http\Controllers\Product\ProductController::class, 'create'])->name('products.create');
        // Route::post('/products', [App\Http\Controllers\Product\ProductController::class, 'store'])->name('products.store');
        // Route::get('/products/{product}', [App\Http\Controllers\Product\ProductController::class, 'adminShow'])->name('products.show');
        // Route::get('/products/{product}/edit', [App\Http\Controllers\Product\ProductController::class, 'edit'])->name('products.edit');
        // Route::put('/products/{product}', [App\Http\Controllers\Product\ProductController::class, 'update'])->name('products.update');
        // Route::delete('/products/{product}', [App\Http\Controllers\Product\ProductController::class, 'destroy'])->name('products.destroy');
        
        // Product Approvals (guru_pkwu)
        // Route::get('/approvals', [App\Http\Controllers\Product\ApprovalController::class, 'index'])->name('approvals.index');
        // Route::post('/approvals/{product}/approve', [App\Http\Controllers\Product\ApprovalController::class, 'approve'])->name('approvals.approve');
        // Route::post('/approvals/{product}/reject', [App\Http\Controllers\Product\ApprovalController::class, 'reject'])->name('approvals.reject');
        
        // Orders Management
        // Route::get('/orders', [App\Http\Controllers\Order\OrderController::class, 'adminIndex'])->name('orders.index');
        // Route::get('/orders/{order}', [App\Http\Controllers\Order\OrderController::class, 'adminShow'])->name('orders.show');
        // Route::put('/orders/{order}/status', [App\Http\Controllers\Order\OrderController::class, 'updateStatus'])->name('orders.update-status');
        
        // Include other module routes
        require __DIR__.'/modules/user.php';
        // require __DIR__.'/modules/academic.php';
        require __DIR__.'/modules/inventory.php';
        require __DIR__.'/modules/payment.php';
        // require __DIR__.'/modules/order.php';
        require __DIR__.'/modules/product.php';
        require __DIR__.'/modules/report.php';
    });
});

Route::middleware(['auth'])->prefix('ajax')->name('ajax.')->group(function () {
    // Cart APIs
    Route::get('/cart', [App\Http\Controllers\Order\CartController::class, 'getCartApi'])->name('cart.get');
    Route::post('/cart/add', [App\Http\Controllers\Order\CartController::class, 'addItemApi'])->name('cart.add');
    Route::put('/cart/items/{cartItem}', [App\Http\Controllers\Order\CartController::class, 'updateItemApi'])->name('cart.update');
    Route::delete('/cart/items/{cartItem}', [App\Http\Controllers\Order\CartController::class, 'removeItemApi'])->name('cart.remove');
    
    // Product APIs for modal
    Route::get('/products/{product}/variants', [App\Http\Controllers\Product\ProductController::class, 'getVariantsApi'])->name('products.variants');
});

// Fallback Route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});