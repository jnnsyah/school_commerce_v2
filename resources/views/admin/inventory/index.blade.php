<!-- resources/views/admin/inventory/index.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Manajemen Stok - School Commerce')
@section('page-title', 'Manajemen Stok')
@section('page-subtitle', 'Overview dan monitoring level persediaan')

@section('content')
<div class="space-y-6">
    <!-- Inventory Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Produk</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['total_products'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Variants -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-layer-group text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Varian</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['total_variants'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Stok Rendah</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['low_stock_count'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Stok Habis</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['out_of_stock_count'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Low Stock Alerts -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-primary dark:text-light">Peringatan Stok Rendah</h3>
                    <a href="{{ route('admin.inventory.variants', ['filter' => 'low_stock']) }}" 
                       class="text-sm text-accent hover:text-accent/80">
                        Lihat Semua
                    </a>
                </div>
                
                <div class="space-y-3">
                    @forelse($lowStockAlerts['low_stock_variants'] as $variant)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/20">
                        <div>
                            <p class="font-medium text-primary dark:text-light">
                                {{ $variant->product->name }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">
                                {{ $variant->name }} - Stok: {{ $variant->stock_at }}
                            </p>
                        </div>
                        <a href="{{ route('admin.inventory.variants') }}?search={{ $variant->product->name }}"
                           class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500 dark:text-slate-400">
                        <i class="fas fa-check-circle text-2xl mb-2"></i>
                        <p>Tidak ada peringatan stok rendah</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Aksi Cepat</h3>
                
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('admin.inventory.variants') }}" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                            <i class="fas fa-layer-group text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-primary dark:text-light">Kelola Stok Varian</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Lihat dan sesuaikan stok varian</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.inventory.extras') }}" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                            <i class="fas fa-plus-circle text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-primary dark:text-light">Kelola Stok Extra</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Kelola stok tambahan produk</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.inventory.movement-history') }}" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                            <i class="fas fa-history text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-primary dark:text-light">Riwayat Stok</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Lihat pergerakan stok</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection<!-- resources/views/admin/inventory/index.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Manajemen Stok - School Commerce')
@section('page-title', 'Manajemen Stok')
@section('page-subtitle', 'Overview dan monitoring level persediaan')

@section('content')
<div class="space-y-6">
    <!-- Inventory Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Produk</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['total_products'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Variants -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-layer-group text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Varian</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['total_variants'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Stok Rendah</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['low_stock_count'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Stok Habis</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['out_of_stock_count'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Low Stock Alerts -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-primary dark:text-light">Peringatan Stok Rendah</h3>
                    <a href="{{ route('admin.inventory.variants', ['filter' => 'low_stock']) }}" 
                       class="text-sm text-accent hover:text-accent/80">
                        Lihat Semua
                    </a>
                </div>
                
                <div class="space-y-3">
                    @forelse($lowStockAlerts['low_stock_variants'] as $variant)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/20">
                        <div>
                            <p class="font-medium text-primary dark:text-light">
                                {{ $variant->product->name }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">
                                {{ $variant->name }} - Stok: {{ $variant->stock_at }}
                            </p>
                        </div>
                        <a href="{{ route('admin.inventory.variants') }}?search={{ $variant->product->name }}"
                           class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500 dark:text-slate-400">
                        <i class="fas fa-check-circle text-2xl mb-2"></i>
                        <p>Tidak ada peringatan stok rendah</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Aksi Cepat</h3>
                
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('admin.inventory.variants') }}" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                            <i class="fas fa-layer-group text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-primary dark:text-light">Kelola Stok Varian</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Lihat dan sesuaikan stok varian</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.inventory.extras') }}" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                            <i class="fas fa-plus-circle text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-primary dark:text-light">Kelola Stok Extra</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Kelola stok tambahan produk</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.inventory.movement-history') }}" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                            <i class="fas fa-history text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-primary dark:text-light">Riwayat Stok</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Lihat pergerakan stok</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection<!-- resources/views/admin/inventory/index.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Manajemen Stok - School Commerce')
@section('page-title', 'Manajemen Stok')
@section('page-subtitle', 'Overview dan monitoring level persediaan')

@section('content')
<div class="space-y-6">
    <!-- Inventory Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Produk</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['total_products'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Variants -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-layer-group text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Varian</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['total_variants'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Stok Rendah</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['low_stock_count'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Out of Stock -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Stok Habis</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $inventorySummary['out_of_stock_count'] ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Low Stock Alerts -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-primary dark:text-light">Peringatan Stok Rendah</h3>
                    <a href="{{ route('admin.inventory.variants', ['filter' => 'low_stock']) }}" 
                       class="text-sm text-accent hover:text-accent/80">
                        Lihat Semua
                    </a>
                </div>
                
                <div class="space-y-3">
                    @forelse($lowStockAlerts['low_stock_variants'] as $variant)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/20">
                        <div>
                            <p class="font-medium text-primary dark:text-light">
                                {{ $variant->product->name }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">
                                {{ $variant->name }} - Stok: {{ $variant->stock_at }}
                            </p>
                        </div>
                        <a href="{{ route('admin.inventory.variants') }}?search={{ $variant->product->name }}"
                           class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500 dark:text-slate-400">
                        <i class="fas fa-check-circle text-2xl mb-2"></i>
                        <p>Tidak ada peringatan stok rendah</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Aksi Cepat</h3>
                
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('admin.inventory.variants') }}" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                            <i class="fas fa-layer-group text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-primary dark:text-light">Kelola Stok Varian</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Lihat dan sesuaikan stok varian</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.inventory.extras') }}" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                            <i class="fas fa-plus-circle text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-primary dark:text-light">Kelola Stok Extra</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Kelola stok tambahan produk</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.inventory.movement-history') }}" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                            <i class="fas fa-history text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <p class="font-medium text-primary dark:text-light">Riwayat Stok</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Lihat pergerakan stok</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection