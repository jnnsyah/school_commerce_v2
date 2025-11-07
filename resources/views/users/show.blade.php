@extends('layouts.app')

@section('title', 'Detail User - ' . $user->name)
@section('page-title', 'Detail User')

@section('page-actions')
    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
    @can('user.edit')
    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>Edit User
    </a>
    @endcan
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- User Profile Card -->
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                <!-- User Avatar -->
                @if($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" 
                     alt="{{ $user->name }}" 
                     class="rounded-circle mb-3" 
                     style="width: 120px; height: 120px; object-fit: cover;">
                @else
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                     style="width: 120px; height: 120px; font-size: 2rem;">
                    {{ substr($user->name, 0, 1) }}
                </div>
                @endif

                <!-- User Info -->
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">@ {{ $user->username }}</p>
                
                <!-- Role Badge -->
                @include('users.partials.role-badge', ['user' => $user])

                <!-- Additional Info -->
                <div class="mt-3">
                    @if($user->teacher)
                    <p class="mb-1">
                        <i class="fas fa-id-card me-2 text-muted"></i>
                        NIP: {{ $user->teacher->nip }}
                    </p>
                    <p class="mb-1">
                        <i class="fas fa-user-tag me-2 text-muted"></i>
                        {{ $user->teacher->getRoleType() }}
                    </p>
                    @endif

                    @if($user->student)
                    <p class="mb-1">
                        <i class="fas fa-id-card me-2 text-muted"></i>
                        NISN: {{ $user->student->nisn }}
                    </p>
                    <p class="mb-1">
                        <i class="fas fa-chalkboard me-2 text-muted"></i>
                        Kelas: {{ $user->student->getClassName() }}
                    </p>
                    @if($user->student->is_admin_class)
                    <span class="badge bg-success">
                        <i class="fas fa-crown me-1"></i>Admin Kelas
                    </span>
                    @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-address-card me-2"></i>Informasi Kontak
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><i class="fas fa-envelope me-2 text-muted"></i></td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-phone me-2 text-muted"></i></td>
                        <td>{{ $user->no_hp }}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-venus-mars me-2 text-muted"></i></td>
                        <td>{{ $user->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-birthday-cake me-2 text-muted"></i></td>
                        <td>{{ $user->birth_date->format('d/m/Y') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Activity Stats -->
        <div class="row mb-4">
            @if($user->hasRole('wali_kelas'))
            <div class="col-md-6">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Kelas yang Diampu</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $user->classes->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chalkboard fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($user->hasRole('student') || $user->hasRole('guru_biasa'))
            <div class="col-md-6">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Pesanan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $user->orders->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($user->hasRole('wali_kelas'))
            <div class="col-md-6">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Produk Kelas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $user->classes->sum(function($class) { return $class->products->count(); }) }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Class Information (for Wali Kelas) -->
        @if($user->hasRole('wali_kelas') && $user->classes->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chalkboard me-2"></i>Kelas yang Diampu
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($user->classes as $class)
                    <div class="col-md-6 mb-3">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h6 class="card-title">{{ $class->getFullName() }}</h6>
                                <p class="card-text small text-muted mb-1">
                                    Siswa: {{ $class->getStudentCount() }}
                                </p>
                                <p class="card-text small text-muted mb-1">
                                    Produk: {{ $class->getActiveProductsCount() }}
                                </p>
                                <a href="{{ route('classes.show', $class) }}" class="btn btn-sm btn-outline-primary">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Orders (for Students & Teachers) -->
        @if(($user->hasRole('student') || $user->hasRole('guru_biasa')) && $user->orders->count() > 0)
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>Pesanan Terbaru
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders->take(5) as $order)
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
                @if($user->orders->count() > 5)
                <div class="text-center mt-3">
                    <a href="{{ route('orders.index') }}?user={{ $user->id }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua Pesanan
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Student Information -->
        @if($user->hasRole('student'))
        <div class="card shadow mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate me-2"></i>Informasi Siswa
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <td width="30%"><strong>NISN</strong></td>
                        <td>{{ $user->student->nisn }}</td>
                    </tr>
                    <tr>
                        <td><strong>Kelas</strong></td>
                        <td>{{ $user->student->getClassName() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status Admin Kelas</strong></td>
                        <td>
                            @if($user->student->is_admin_class)
                            <span class="badge bg-success">Ya</span>
                            @else
                            <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Wali Kelas</strong></td>
                        <td>{{ $user->student->class->teacher->name }}</td>
                    </tr>
                </table>
            </div>
        </div>
        @endif

        <!-- Teacher Information -->
        @if($user->hasRole(['guru_pkwu', 'wali_kelas', 'guru_biasa']))
        <div class="card shadow mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chalkboard-teacher me-2"></i>Informasi Guru
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <td width="30%"><strong>NIP</strong></td>
                        <td>{{ $user->teacher->nip }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jenis Guru</strong></td>
                        <td>{{ $user->teacher->getRoleType() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status PKWU</strong></td>
                        <td>
                            @if($user->teacher->is_pkwu)
                            <span class="badge bg-success">Ya</span>
                            @else
                            <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Status Wali Kelas</strong></td>
                        <td>
                            @if($user->teacher->is_wali_kelas)
                            <span class="badge bg-success">Ya</span>
                            @else
                            <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection