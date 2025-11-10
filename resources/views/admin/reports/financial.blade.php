<!-- resources/views/admin/reports/financial.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Laporan Keuangan - School Commerce')
@section('page-title', 'Laporan Keuangan')
@section('page-subtitle', 'Analisis keuangan dan revenue streams')

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

    <!-- Revenue by Status -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Status Breakdown -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-primary dark:text-light mb-6">Revenue per Status</h3>
            <div class="space-y-4">
                @php
                    $statusNames = [
                        1 => 'Menunggu Pembayaran',
                        2 => 'Dibayar',
                        3 => 'Diproses', 
                        4 => 'Selesai',
                        5 => 'Dibatalkan'
                    ];
                    
                    $statusColors = [
                        1 => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
                        2 => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
                        3 => 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300',
                        4 => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
                        5 => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300'
                    ];
                @endphp
                
                @foreach($financialData as $data)
                <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-slate-600">
                    <div class="flex items-center space-x-3">
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$data->status_id] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ $statusNames[$data->status_id] ?? 'Unknown' }}
                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-accent">
                            Rp {{ number_format($data->total_amount, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-slate-400">
                            {{ $data->order_count }} pesanan
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-semibold text-primary dark:text-light mb-6">Revenue Bulanan ({{ date('Y') }})</h3>
            <div class="h-64">
                <canvas id="monthlyRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Class Revenue Performance -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-primary dark:text-light mb-6">Perform Revenue per Kelas</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Total Revenue
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Ranking
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @foreach($classRevenue as $index => $class)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-primary dark:text-light">
                                Kelas {{ $class->grade_id }} - {{ $class->major_id }} {{ $class->section_id }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-accent">
                                Rp {{ number_format($class->revenue, 0, ',', '.') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm text-gray-900 dark:text-light mr-2">
                                    #{{ $index + 1 }}
                                </span>
                                @if($index == 0)
                                <i class="fas fa-trophy text-yellow-500"></i>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Revenue Chart
const monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
        datasets: [{
            label: 'Revenue (Rp)',
            data: @json(array_fill(0, 12, 0)), // Placeholder - need actual data
            backgroundColor: '#00bba7',
            borderColor: '#00bba7',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
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

// Update chart with actual data
@if($monthlyRevenue->isNotEmpty())
    const monthlyData = @json($monthlyRevenue);
    monthlyChart.data.datasets[0].data = Array.from({length: 12}, (_, i) => {
        const monthData = monthlyData.find(d => d.month === i + 1);
        return monthData ? monthData.revenue : 0;
    });
    monthlyChart.update();
@endif
</script>
@endsection