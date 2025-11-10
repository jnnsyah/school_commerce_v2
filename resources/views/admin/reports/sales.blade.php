<!-- resources/views/admin/reports/sales.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Laporan Penjualan - School Commerce')
@section('page-title', 'Laporan Penjualan')
@section('page-subtitle', 'Analisis performa penjualan dan revenue')

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

    <!-- Sales Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Orders -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-shopping-cart text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Pesanan</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $salesData->total_orders ?? 0 }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-accent mt-1">
                        Rp {{ number_format($salesData->total_revenue ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Average Order Value -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Rata-rata Pesanan</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        Rp {{ number_format($salesData->average_order_value ?? 0, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Unique Customers -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Pelanggan Unik</p>
                    <p class="text-2xl font-bold text-primary dark:text-light mt-1">
                        {{ $salesData->unique_customers ?? 0 }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Daily Sales Chart -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light">Penjualan Harian</h3>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600 dark:text-slate-400">Periode:</span>
                    <span class="text-sm font-medium text-primary dark:text-light">
                        {{ $dateRange['start']->format('d M Y') }} - {{ $dateRange['end']->format('d M Y') }}
                    </span>
                </div>
            </div>
            <div class="h-80">
                <canvas id="dailySalesChart"></canvas>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-primary dark:text-light mb-6">Produk Terlaris</h3>
            <div class="space-y-4">
                @forelse($topProducts as $product)
                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-slate-600">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gray-100 dark:bg-slate-700 rounded flex items-center justify-center">
                            <i class="fas fa-box text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-medium text-primary dark:text-light text-sm">
                                {{ Str::limit($product->name, 30) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-slate-400">
                                Terjual: {{ $product->total_sold }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-accent text-sm">
                            Rp {{ number_format($product->revenue, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500 dark:text-slate-400">
                    <i class="fas fa-chart-bar text-3xl mb-2"></i>
                    <p>Tidak ada data penjualan</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Export Actions -->
    {{-- <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-primary dark:text-light">Ekspor Laporan</h3>
                <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                    Download laporan dalam format Excel atau PDF
                </p>
            </div>
            <div class="flex space-x-3">
                <button onclick="exportSales('excel')"
                        class="flex items-center space-x-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <i class="fas fa-file-excel"></i>
                    <span>Excel</span>
                </button>
                <button onclick="exportSales('pdf')"
                        class="flex items-center space-x-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    <i class="fas fa-file-pdf"></i>
                    <span>PDF</span>
                </button>
            </div>
        </div>
    </div> --}}
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Daily Sales Chart
const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
const dailySalesChart = new Chart(dailySalesCtx, {
    type: 'line',
    data: {
        labels: @json($dailySales->pluck('date')),
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: @json($dailySales->pluck('revenue')),
            borderColor: '#00bba7',
            backgroundColor: 'rgba(0, 187, 167, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});

function exportSales(format) {
    const startDate = document.querySelector('input[name="start_date"]').value;
    const endDate = document.querySelector('input[name="end_date"]').value;
    
    fetch(`/admin/reports/export-sales?format=${format}&start_date=${startDate}&end_date=${endDate}`, {
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