<!-- resources/views/admin/dashboard/index.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Merchant Dashboard - School Commerce')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan aktivitas dan performa')

@section('content')
<div class="space-y-6">
    <!-- Role-based Welcome -->
    <div class="bg-gradient-to-r from-accent to-blue-600 rounded-lg p-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">
                    Selamat Datang, {{ auth()->user()->name }}!
                </h1>
                <p class="opacity-90">
                    @if(auth()->user()->hasRole('super_admin'))
                        Anda memiliki akses penuh ke semua fitur sistem
                    @elseif(auth()->user()->hasRole('admin'))
                        Kelola seluruh operasional sekolah commerce
                    @elseif(auth()->user()->hasRole('guru_pkwu'))
                        Pantau dan setujui produk siswa
                    @elseif(auth()->user()->hasRole('wali_kelas'))
                        Kelola produk dan pesanan kelas Anda
                    @else
                        Selamat beraktivitas di School Commerce
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products -->
        @include('components.admin.metric-card', [
            'title' => 'Total Produk',
            'value' => $data['totalProducts'] ?? 0,
            'icon' => 'fa-box',
            'color' => 'blue',
            'link' => '/admin/products',
            'linkText' => 'Lihat Produk'
        ])

        <!-- Total Orders -->
        @include('components.admin.metric-card', [
            'title' => 'Total Pesanan',
            'value' => $data['totalOrders'] ?? 0,
            'icon' => 'fa-shopping-cart',
            'color' => 'green',
            'link' => '/admin/orders',
            'linkText' => 'Lihat Pesanan'
        ])

        <!-- Total Revenue -->
        @include('components.admin.metric-card', [
            'title' => 'Total Pendapatan',
            'value' => 'Rp ' . number_format($data['totalRevenue'] ?? 0, 0, ',', '.'),
            'icon' => 'fa-money-bill-wave',
            'color' => 'purple',
            'link' => '/admin/reports/financial',
            'linkText' => 'Lihat Laporan'
        ])

        <!-- Pending Approvals -->
        @if(isset($data['pendingApprovals']))
        @include('components.admin.metric-card', [
            'title' => 'Menunggu Persetujuan',
            'value' => $data['pendingApprovals'],
            'icon' => 'fa-clock',
            'color' => 'yellow',
            'link' => '/admin/approvals',
            'linkText' => 'Review Sekarang'
        ])
        @endif

        <!-- Class Products (for wali_kelas) -->
        @if(isset($data['classProducts']))
        @include('components.admin.metric-card', [
            'title' => 'Produk Kelas',
            'value' => $data['classProducts'],
            'icon' => 'fa-school',
            'color' => 'indigo',
            'link' => '/admin/products?class_id=' . ($data['class']->class_id ?? ''),
            'linkText' => 'Kelola Produk'
        ])
        @endif

        <!-- Pending Products (for wali_kelas) -->
        @if(isset($data['pendingProducts']))
        @include('components.admin.metric-card', [
            'title' => 'Produk Pending',
            'value' => $data['pendingProducts'],
            'icon' => 'fa-hourglass',
            'color' => 'orange',
            'link' => '/admin/products?status=pending',
            'linkText' => 'Review Produk'
        ])
        @endif

        <!-- Class Students (for wali_kelas) -->
        @if(isset($data['classStudents']))
        @include('components.admin.metric-card', [
            'title' => 'Siswa Kelas',
            'value' => $data['classStudents'],
            'icon' => 'fa-users',
            'color' => 'teal',
            'link' => '/admin/students',
            'linkText' => 'Lihat Siswa'
        ])
        @endif

        <!-- Low Stock Alerts -->
        @if(isset($data['lowStockCount']))
        @include('components.admin.metric-card', [
            'title' => 'Stok Rendah',
            'value' => $data['lowStockCount'],
            'icon' => 'fa-exclamation-triangle',
            'color' => 'red',
            'link' => '/admin/inventory',
            'linkText' => 'Cek Stok'
        ])
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Orders -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-primary dark:text-light">Pesanan Terbaru</h3>
                    <a href="/admin/orders" class="text-sm text-accent hover:text-accent/80 flex items-center">
                        Lihat Semua
                        <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
                
                <div class="space-y-3">
                    @forelse(($data['recentOrders'] ?? []) as $order)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-accent/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-receipt text-accent"></i>
                            </div>
                            <div>
                                <p class="font-medium text-primary dark:text-light text-sm">
                                    {{ $order->user->name }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-slate-400">
                                    #{{ $order->invoice_number }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-accent text-sm">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </p>
                            <span class="inline-block px-2 py-1 text-xs rounded-full 
                                @if($order->status_id == 1) bg-yellow-100 text-yellow-800
                                @elseif($order->status_id == 2) bg-blue-100 text-blue-800
                                @elseif($order->status_id == 3) bg-purple-100 text-purple-800
                                @elseif($order->status_id == 4) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $order->status->name }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-slate-400">
                        <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                        <p class="text-sm">Belum ada pesanan</p>
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
                    @if(auth()->user()->hasRole(['wali_kelas', 'admin']))
                    <a href="/admin/products/create" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition group">
                        <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center group-hover:scale-110 transition">
                            <i class="fas fa-plus text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-primary dark:text-light">Tambah Produk Baru</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Buat listing produk baru dengan variants</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-accent transition"></i>
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('guru_pkwu') && ($data['pendingApprovals'] ?? 0) > 0)
                    <a href="/admin/approvals" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800 
                              bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition group">
                        <div class="w-12 h-12 rounded-full bg-yellow-100 dark:bg-yellow-900/20 flex items-center justify-center group-hover:scale-110 transition">
                            <i class="fas fa-check-circle text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-primary dark:text-light">Kelola Persetujuan</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">
                                {{ $data['pendingApprovals'] }} produk menunggu review
                            </p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-accent transition"></i>
                    </a>
                    @endif

                    <a href="/admin/orders" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition group">
                        <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center group-hover:scale-110 transition">
                            <i class="fas fa-shopping-cart text-green-600 dark:text-green-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-primary dark:text-light">Kelola Pesanan</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Lihat dan proses pesanan terbaru</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-accent transition"></i>
                    </a>

                    @if(isset($data['lowStockCount']) && $data['lowStockCount'] > 0)
                    <a href="/admin/inventory" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-red-200 dark:border-red-800 
                              bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 transition group">
                        <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/20 flex items-center justify-center group-hover:scale-110 transition">
                            <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-primary dark:text-light">Cek Stok Rendah</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">
                                {{ $data['lowStockCount'] }} produk perlu restock
                            </p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-accent transition"></i>
                    </a>
                    @else
                    <a href="/admin/inventory" 
                       class="flex items-center space-x-3 p-4 rounded-lg border border-gray-200 dark:border-slate-600 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition group">
                        <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center group-hover:scale-110 transition">
                            <i class="fas fa-warehouse text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-primary dark:text-light">Kelola Stok</p>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Pantau level persediaan produk</p>
                        </div>
                        <i class="fas fa-arrow-right text-gray-400 group-hover:text-accent transition"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Widgets Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Activities -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-3">
                    @foreach($data['recentActivities'] ?? [] as $activity)
                    <div class="flex items-start space-x-3 p-3 rounded-lg border border-gray-200 dark:border-slate-600">
                        <div class="w-8 h-8 rounded-full bg-accent/10 flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $activity['icon'] }} text-accent text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-primary dark:text-light">{{ $activity['description'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                    @endforeach
                    
                    @empty($data['recentActivities'] ?? [])
                    <div class="text-center py-8 text-gray-500 dark:text-slate-400">
                        <i class="fas fa-history text-3xl mb-2"></i>
                        <p class="text-sm">Belum ada aktivitas terbaru</p>
                    </div>
                    @endempty
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Status Sistem</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-slate-400">Aplikasi</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            Online
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-slate-400">Database</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            Connected
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-slate-400">Payment Gateway</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            Active
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-slate-400">Storage</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300">
                            <i class="fas fa-circle mr-1 text-xs"></i>
                            {{ $data['storageUsage'] ?? '65%' }} Used
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection