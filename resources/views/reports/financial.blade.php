@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Date Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ $dateRange['start']->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ $dateRange['end']->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Filter
                            </button>
                            <a href="{{ route('reports.financial') }}" class="btn btn-secondary">
                                <i class="fas fa-refresh me-1"></i>Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Financial Summary -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Pendapatan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($financialSummary['total_revenue']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Rata-rata per Kelas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($financialSummary['average_revenue_per_class']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chalkboard fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Transaksi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $financialSummary['total_transactions'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-receipt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Kelas Teratas</div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    @if($financialSummary['top_performing_class'])
                                        {{ $financialSummary['top_performing_class']->getFullName() }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-600 mt-1">
                                    @if($financialSummary['top_performing_class'])
                                        Rp {{ number_format($financialSummary['top_performing_class']->class_revenue) }}
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-trophy fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue by Class -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Pendapatan per Kelas
                </h5>
                <span class="badge bg-primary">
                    Total Kelas: {{ $revenueByClass->count() }}
                </span>
            </div>
            <div class="card-body">
                @if($revenueByClass->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="5%">#</th>
                                <th>Kelas</th>
                                <th>Wali Kelas</th>
                                <th width="15%">Total Pendapatan</th>
                                <th width="20%">Persentase</th>
                                <th width="10%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revenueByClass as $index => $class)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong>{{ $class->getFullName() }}</strong>
                                </td>
                                <td>
                                    @if($class->teacher)
                                        {{ $class->teacher->name }}
                                    @else
                                        <span class="text-muted">Belum ada wali kelas</span>
                                    @endif
                                </td>
                                <td class="text-success text-end">
                                    <strong>Rp {{ number_format($class->class_revenue) }}</strong>
                                </td>
                                <td>
                                    @php
                                        $percentage = $financialSummary['total_revenue'] > 0 ? 
                                                    ($class->class_revenue / $financialSummary['total_revenue']) * 100 : 0;
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 me-3">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $percentage }}%;" 
                                                     aria-valuenow="{{ $percentage }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-nowrap">
                                            {{ number_format($percentage, 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($class->class_revenue > 0)
                                    <span class="badge bg-success">Aktif</span>
                                    @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end text-success">
                                    <strong>Rp {{ number_format($financialSummary['total_revenue']) }}</strong>
                                </td>
                                <td>100%</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data pendapatan per kelas dalam periode yang dipilih</p>
                    <a href="{{ route('reports.financial') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-1"></i>Lihat Semua Data
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Monthly Revenue Trend -->
        <div class="card shadow">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Trend Pendapatan Bulanan (12 Bulan Terakhir)
                </h5>
                <span class="badge bg-info">
                    Periode: {{ $dateRange['start']->subYear()->format('M Y') }} - {{ $dateRange['end']->format('M Y') }}
                </span>
            </div>
            <div class="card-body">
                @if($monthlyRevenue->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th width="20%">Bulan</th>
                                <th width="20%">Pendapatan</th>
                                <th width="15%">Pertumbuhan</th>
                                <th width="45%">Visual Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $previousRevenue = null;
                            @endphp
                            @foreach($monthlyRevenue as $revenue)
                            <tr>
                                <td>
                                    <strong>{{ \Carbon\Carbon::create($revenue->year, $revenue->month)->locale('id')->translatedFormat('F Y') }}</strong>
                                </td>
                                <td class="text-success text-end">
                                    <strong>Rp {{ number_format($revenue->revenue) }}</strong>
                                </td>
                                <td class="text-center">
                                    @if($previousRevenue === null)
                                    <span class="badge bg-secondary">-</span>
                                    @else
                                    @php
                                        $growth = $previousRevenue > 0 ? (($revenue->revenue - $previousRevenue) / $previousRevenue) * 100 : 0;
                                    @endphp
                                    <span class="badge bg-{{ $growth >= 0 ? 'success' : 'danger' }}">
                                        {{ $growth >= 0 ? '↑' : '↓' }} {{ number_format(abs($growth), 1) }}%
                                    </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $maxRevenue = $monthlyRevenue->max('revenue');
                                        $barWidth = $maxRevenue > 0 ? ($revenue->revenue / $maxRevenue) * 100 : 0;
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 me-3">
                                            <div class="progress" style="height: 12px;">
                                                <div class="progress-bar bg-info" role="progressbar" 
                                                     style="width: {{ $barWidth }}%;" 
                                                     aria-valuenow="{{ $barWidth }}" 
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-muted small">
                                            {{ number_format($barWidth, 1) }}% dari tertinggi
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @php
                                $previousRevenue = $revenue->revenue;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Simple Chart Visualization -->
                <div class="mt-4">
                    <h6 class="text-center mb-3">Grafik Trend Pendapatan Bulanan</h6>
                    <div class="chart-container" style="height: 200px;">
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data trend pendapatan bulanan</p>
                    <p class="text-muted small">Data akan tersedia setelah ada transaksi yang selesai dalam 12 bulan terakhir</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Additional Summary -->
        @if($revenueByClass->count() > 0)
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Ringkasan Performa
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Kelas dengan Pendapatan Tertinggi</small>
                                <p class="mb-1">
                                    <strong>
                                        @if($financialSummary['top_performing_class'])
                                            {{ $financialSummary['top_performing_class']->getFullName() }}
                                        @else
                                            -
                                        @endif
                                    </strong>
                                </p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Rata-rata Transaksi</small>
                                <p class="mb-1">
                                    <strong>
                                        Rp {{ number_format($financialSummary['total_revenue'] / max($financialSummary['total_transactions'], 1)) }}
                                    </strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-calendar me-2"></i>Periode Laporan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Tanggal Mulai</small>
                                <p class="mb-1"><strong>{{ $dateRange['start']->format('d M Y') }}</strong></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Tanggal Akhir</small>
                                <p class="mb-1"><strong>{{ $dateRange['end']->format('d M Y') }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if($monthlyRevenue->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('monthlyRevenueChart').getContext('2d');
    const monthlyData = @json($monthlyRevenue);
    
    const labels = monthlyData.map(item => {
        const date = new Date(item.year, item.month - 1);
        return date.toLocaleDateString('id-ID', { month: 'short', year: 'numeric' });
    });
    
    const revenues = monthlyData.map(item => item.revenue);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan',
                data: revenues,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
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
});
</script>
@endif
@endpush