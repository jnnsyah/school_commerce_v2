<!-- resources/views/admin/reports/products.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Laporan Produk - School Commerce')
@section('page-title', 'Laporan Performa Produk')
@section('page-subtitle', 'Analisis penjualan dan performa produk')

@section('content')
<div class="space-y-6">
    <!-- Date Range Filter -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Filter Laporan</h3>
        <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Tanggal Mulai</label>
                <input type="date" 
                       name="start_date" 
                       value="{{ request('start_date', $dateRange['start']->format('Y-m-d')) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                              focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                              bg-white dark:bg-secondary text-primary dark:text-light">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Tanggal Akhir</label>
                <input type="date" 
                       name="end_date" 
                       value="{{ request('end_date', $dateRange['end']->format('Y-m-d')) }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                              focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                              bg-white dark:bg-secondary text-primary dark:text-light">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                    <i class="fas fa-filter mr-2"></i>
                    Terapkan
                </button>
                <a href="{{ url()->current() }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                    <i class="fas fa-refresh"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Category Performance -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-primary dark:text-light mb-6">Perform per Kategori</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Terjual
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Pendapatan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Rata-rata
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($categoryPerformance as $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-primary dark:text-light">
                                {{ $category->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-light">
                                {{ $category->total_sold }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-accent">
                                Rp {{ number_format($category->revenue, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-light">
                                Rp {{ number_format($category->revenue / max($category->total_sold, 1), 0, ',', '.') }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-slate-400">
                            <i class="fas fa-chart-pie text-3xl mb-2"></i>
                            <p>Tidak ada data kategori</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Product Performance Table -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
        <div class="p-6 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-primary dark:text-light">Detail Performa Produk</h3>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Total {{ $productPerformance->total() }} produk ditemukan
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Produk
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Kategori
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Terjual
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Pendapatan
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($productPerformance as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
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
                                        {{ Str::limit($product->name, 30) }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-slate-400">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-light">
                                {{ $product->category->name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-light">
                                {{ $product->class->grade->name }} {{ $product->class->major->short_name }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-light">
                                {{ $product->total_sold ?? 0 }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-accent">
                                Rp {{ number_format($product->total_revenue ?? 0, 0, ',', '.') }}
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">
                            <i class="fas fa-box text-3xl mb-2"></i>
                            <p>Tidak ada data produk</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($productPerformance->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
            {{ $productPerformance->links('components.shared.pagination') }}
        </div>
        @endif
    </div>

    <!-- Export Actions -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-primary dark:text-light">Ekspor Laporan Produk</h3>
                <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                    Download laporan performa produk
                </p>
            </div>
            <div class="flex space-x-3">
                <button onclick="exportProducts('excel')"
                        class="flex items-center space-x-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-excel"></i>
                    <span>Excel</span>
                </button>
                <button onclick="exportProducts('pdf')"
                        class="flex items-center space-x-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-file-pdf"></i>
                    <span>PDF</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function exportProducts(format) {
    const startDate = document.querySelector('input[name="start_date"]').value;
    const endDate = document.querySelector('input[name="end_date"]').value;
    
    fetch(`/admin/reports/export-products?format=${format}&start_date=${startDate}&end_date=${endDate}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.url) {
            window.open(data.url, '_blank');
        } else {
            alert('Fitur ekspor akan segera hadir');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengekspor');
    });
}
</script>
@endsection