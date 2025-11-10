<!-- resources/views/admin/products/index.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Kelola Produk - School Commerce')
@section('page-title', 'Kelola Produk')
@section('page-subtitle', 'Daftar semua produk yang dikelola')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-primary dark:text-light">Daftar Produk</h2>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Total {{ $products->total() }} produk ditemukan
            </p>
        </div>
        
        @if(auth()->user()->hasRole(['wali_kelas', 'admin']))
        <div class="mt-4 sm:mt-0">
            <a href="/admin/products/create" 
               class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                <i class="fas fa-plus mr-2"></i>
                Tambah Produk
            </a>
        </div>
        @endif
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-4">
        <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                           focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                           bg-white dark:bg-secondary text-primary dark:text-light">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->id }}" {{ request('status') == $status->id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Category Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Kategori</label>
                <select name="category_id" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                                focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                                bg-white dark:bg-secondary text-primary dark:text-light">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Class Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Kelas</label>
                <select name="class_id" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                             focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                             bg-white dark:bg-secondary text-primary dark:text-light">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->class_id }}" {{ request('class_id') == $class->class_id ? 'selected' : '' }}>
                            {{ $class->grade->name }} {{ $class->major->short_name }} {{ $class->section->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ url()->current() }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                    <i class="fas fa-refresh"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Produk
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Dibuat
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <!-- Product Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($product->images->count() > 0)
                                        <img class="h-10 w-10 rounded object-cover" 
                                             src="{{ asset('storage/' . $product->images->first()->file_path) }}" 
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded bg-gray-100 dark:bg-slate-700 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-primary dark:text-light">
                                        {{ $product->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-slate-400">
                                        {{ $product->category->name }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Class -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-primary dark:text-light">
                                {{ $product->class->grade->name }} {{ $product->class->major->short_name }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-slate-400">
                                {{ $product->class->section->name }}
                            </div>
                        </td>

                        <!-- Price -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-accent">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @include('components.admin.approval-badge', ['status' => $product->status])
                        </td>

                        <!-- Created At -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                            {{ $product->created_at->format('d M Y') }}
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="/admin/products/{{ $product->product_id }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($product->class->teacher_id == auth()->id() || auth()->user()->hasRole(['super_admin', 'admin']))
                                <a href="/admin/products/{{ $product->product_id }}/edit" 
                                   class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                @if(($product->class->teacher_id == auth()->id() || auth()->user()->hasRole(['super_admin', 'admin'])) && !$product->isApproved())
                                <form action="/admin/products/{{ $product->product_id }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-box text-4xl text-gray-400 dark:text-slate-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-light mb-2">Tidak ada produk</h3>
                            <p class="text-gray-500 dark:text-slate-400 mb-4">Belum ada produk yang dibuat</p>
                            @if(auth()->user()->hasRole(['wali_kelas', 'admin']))
                            <a href="/admin/products/create" 
                               class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Produk Pertama
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
            {{ $products->links('components.shared.pagination') }}
        </div>
        @endif
    </div>
</div>
@endsection