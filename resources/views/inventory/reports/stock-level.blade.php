@extends('layouts.app')

@section('title', 'Laporan Level Stok')
@section('page-title', 'Laporan Level Stok')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Summary Cards -->
        <div class="card border-left-danger shadow mb-4">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Stok Habis</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $outOfStockProducts->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-left-warning shadow mb-4">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Stok Menipis</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $lowStockProducts->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Out of Stock Products -->
        <div class="card shadow mb-4">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-times-circle me-2"></i>Produk Stok Habis
                </h5>
            </div>
            <div class="card-body">
                @if($outOfStockProducts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kelas</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($outOfStockProducts as $product)
                            <tr>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $product->category->name }}</small>
                                </td>
                                <td>{{ $product->class->getFullName() }}</td>
                                <td>
                                    <span class="badge bg-danger">0 unit</span>
                                </td>
                                <td>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventory.stock.index') }}#adjustStockModal{{ $product->product_id }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                    <p class="text-success">Tidak ada produk yang stok habis</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Produk Stok Menipis
                </h5>
            </div>
            <div class="card-body">
                @if($lowStockProducts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kelas</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lowStockProducts as $product)
                            <tr>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $product->category->name }}</small>
                                </td>
                                <td>{{ $product->class->getFullName() }}</td>
                                <td>
                                    <span class="badge bg-warning">{{ $product->getStockQuantity() }} unit</span>
                                </td>
                                <td>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventory.stock.index') }}#adjustStockModal{{ $product->product_id }}" 
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                    <p class="text-success">Tidak ada produk yang stok menipis</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <a href="{{ route('inventory.stock.index') }}" class="btn btn-primary btn-lg w-100 mb-2">
                            <i class="fas fa-edit me-2"></i>Kelola Stok
                        </a>
                        <small class="text-muted">Adjust stok produk</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <a href="{{ route('inventory.reports.movement') }}" class="btn btn-info btn-lg w-100 mb-2">
                            <i class="fas fa-history me-2"></i>Riwayat Stok
                        </a>
                        <small class="text-muted">Lihat riwayat perpindahan</small>
                    </div>
                    <div class="col-md-4 text-center">
                        <a href="{{ route('products.index') }}" class="btn btn-success btn-lg w-100 mb-2">
                            <i class="fas fa-box me-2"></i>Daftar Produk
                        </a>
                        <small class="text-muted">Lihat semua produk</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection