@extends('layouts.app')

@section('title', 'Laporan Produk')
@section('page-title', 'Laporan Performa Produk')

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
                        <label for="category_id" class="form-label">Kategori</label>
                        <select name="category_id" class="form-select">
                            <option value="">Semua Kategori</option>
                            <!-- Categories would be populated here -->
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

        <!-- Product Performance Summary -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Produk</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $productPerformance['total_products'] }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
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
                                    Produk Aktif</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $productPerformance['active_products'] }}
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
                                    Total Terjual</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $productPerformance['total_items_sold'] }}
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
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Pendapatan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($productPerformance['total_product_revenue']) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>Produk Terlaris
                </h5>
            </div>
            <div class="card-body">
                @if($topProducts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Peringkat</th>
                                <th>Produk</th>
                                <th>Kelas</th>
                                <th>Terjual</th>
                                <th>Pendapatan</th>
                                <th>Rating</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $index => $product)
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
                                    <strong>{{ $product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $product->category->name }}</small>
                                </td>
                                <td>{{ $product->class->getFullName() }}</td>
                                <td>
                                    <span class="badge bg-primary">{{ $product->total_sold }} unit</span>
                                </td>
                                <td class="text-success">
                                    <strong>Rp {{ number_format($product->total_revenue) }}</strong>
                                </td>
                                <td>
                                    @php
                                        $rating = $product->total_sold > 10 ? 5 : 
                                                 ($product->total_sold > 5 ? 4 : 
                                                 ($product->total_sold > 2 ? 3 : 2));
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $rating ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($topProducts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $topProducts->links() }}
                </div>
                @endif
                @else
                <div class="text-center py-4">
                    <i class="fas fa-chart-bar fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada data performa produk</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection