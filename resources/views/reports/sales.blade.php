@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Date Filter -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ $dateRange['start']->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ $dateRange['end']->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status Order</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Completed</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Paid</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sales Summary -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Pesanan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $salesData['total_orders'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
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
                                    Pesanan Selesai</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $salesData['completed_orders'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                    Total Pendapatan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($salesData['total_revenue']) }}
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
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Rata-rata Pesanan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($salesData['average_order_value']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Classes -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>Kelas dengan Penjualan Tertinggi
                </h5>
            </div>
            <div class="card-body">
                @if($topClasses->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Peringkat</th>
                                <th>Kelas</th>
                                <th>Total Produk</th>
                                <th>Total Pendapatan</th>
                                <th>Wali Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topClasses as $index => $class)
                            <tr>
                                <td>
                                    @if($index == 0)
                                    <span class="badge bg-warning">🥇</span>
                                    @elseif($index == 1)
                                    <span class="badge bg-secondary">🥈</span>
                                    @elseif($index == 2)
                                    <span class="badge bg-danger">🥉</span>
                                    @else
                                    <span class="badge bg-light text-dark">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $class->getFullName() }}</strong>
                                </td>
                                <td>{{ $class->products_count ?? 0 }} produk</td>
                                <td class="text-success">
                                    <strong>Rp {{ number_format($class->revenue ?? 0) }}</strong>
                                </td>
                                <td>{{ $class->teacher->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-bar fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data penjualan</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Daily Sales Trend -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>Trend Penjualan Harian
                </h5>
            </div>
            <div class="card-body">
                @if($dailySales->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jumlah Pesanan</th>
                                <th>Total Pendapatan</th>
                                <th>Rata-rata per Pesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dailySales as $sales)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($sales->date)->format('d/m/Y') }}</td>
                                <td>{{ $sales->orders }} pesanan</td>
                                <td class="text-success">
                                    <strong>Rp {{ number_format($sales->revenue) }}</strong>
                                </td>
                                <td>Rp {{ number_format($sales->revenue / max($sales->orders, 1)) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-line fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data trend penjualan</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection