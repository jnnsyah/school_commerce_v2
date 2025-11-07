@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    
    <!-- Admin & Super Admin Dashboard -->
    @hasanyrole('super_admin|admin')
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['totalUsers'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
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
                            Total Kelas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['totalClasses'] ?? 0 }}
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
                            Total Produk</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['totalProducts'] ?? 0 }}
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
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Approval</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['pendingProducts'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endhasanyrole

    <!-- Guru PKWU Dashboard -->
    @hasrole('guru_pkwu')
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Menunggu Approval</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['pendingApprovals'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            Produk Disetujui</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['approvedProducts'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endhasrole

    <!-- Wali Kelas Dashboard -->
    @hasrole('wali_kelas')
    @if(isset($data['class']))
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Kelas Saya</div>
                        <div class="h6 mb-0 font-weight-bold text-gray-800">
                            {{ $data['class']->getFullName() }}
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
                            Produk Kelas</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['classProducts'] ?? 0 }}
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
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Approval</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['pendingProducts'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            Siswa</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $data['classStudents'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-graduate fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endhasrole
</div>

<!-- Recent Products for Students & Teachers -->
@hasanyrole('student|guru_biasa')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Produk Terbaru</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($data['recentProducts'] ?? [] as $product)
                    <div class="col-lg-4 col-md-6 mb-4">
                        @include('products.partials.product-card', ['product' => $product])
                    </div>
                    @endforeach
                </div>
                @if(empty($data['recentProducts']))
                <p class="text-muted text-center">Belum ada produk tersedia.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endhasanyrole

<!-- Recent Orders -->
@if(isset($data['recentOrders']) && $data['recentOrders']->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h6>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['recentOrders'] as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}">{{ $order->invoice_number }}</a>
                                </td>
                                <td>Rp {{ number_format($order->total_amount) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status->name == 'completed' ? 'success' : 'warning' }}">
                                        {{ $order->status->name }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection