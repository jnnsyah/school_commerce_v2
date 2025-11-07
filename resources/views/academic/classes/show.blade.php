@extends('layouts.app')

@section('title', 'Detail Kelas - ' . $class->getFullName())
@section('page-title', 'Detail Kelas')

@section('page-actions')
    <a href="{{ route('classes.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
    @can('class.edit')
    <a href="{{ route('classes.edit', $class) }}" class="btn btn-warning">
        <i class="fas fa-edit me-1"></i>Edit Kelas
    </a>
    @endcan
    @can('class.manage')
    <a href="{{ route('classes.students.manage', $class) }}" class="btn btn-primary">
        <i class="fas fa-users me-1"></i>Kelola Siswa
    </a>
    @endcan
@endsection

@section('content')
<div class="row">
    <div class="col-lg-4">
        <!-- Class Info Card -->
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">{{ $class->getFullName() }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td><strong>Tingkatan:</strong></td>
                        <td>{{ $class->grade->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Jurusan:</strong></td>
                        <td>{{ $class->major->name }} ({{ $class->major->short_name }})</td>
                    </tr>
                    <tr>
                        <td><strong>Section:</strong></td>
                        <td>{{ $class->section->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Wali Kelas:</strong></td>
                        <td>{{ $class->teacher->name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($class->is_active)
                            <span class="badge bg-success">Aktif</span>
                            @else
                            <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Statistics -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Statistik
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="h3 text-primary">{{ $class->getStudentCount() }}</div>
                        <small class="text-muted">Siswa</small>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="h3 text-success">{{ $class->getActiveProductsCount() }}</div>
                        <small class="text-muted">Produk Aktif</small>
                    </div>
                    <div class="col-6">
                        <div class="h3 text-info">{{ $class->products->count() }}</div>
                        <small class="text-muted">Total Produk</small>
                    </div>
                    <div class="col-6">
                        <div class="h3 text-warning">{{ $class->products->where('status_id', 1)->count() }}</div>
                        <small class="text-muted">Pending Approval</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Students List -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Daftar Siswa ({{ $class->students->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($class->students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Username</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->students as $student)
                            <tr>
                                <td>
                                    <strong>{{ $student->user->name }}</strong>
                                    @if($student->is_admin_class)
                                    <span class="badge bg-success ms-1">Admin</span>
                                    @endif
                                </td>
                                <td>{{ $student->nisn }}</td>
                                <td>@ {{ $student->user->username }}</td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-user-graduate fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada siswa di kelas ini</p>
                    @can('class.manage')
                    <a href="{{ route('classes.students.manage', $class) }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i>Tambah Siswa
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>

        <!-- Class Products -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-box me-2"></i>Produk Kelas ({{ $class->products->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($class->products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->products as $product)
                            <tr>
                                <td>
                                    <strong>{{ $product->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                </td>
                                <td>{{ $product->category->name }}</td>
                                <td>Rp {{ number_format($product->price) }}</td>
                                <td>
                                    @include('products.partials.status-badge', ['status' => $product->status])
                                </td>
                                <td>
                                    <span class="badge bg-{{ $product->getStockQuantity() > 0 ? 'success' : 'danger' }}">
                                        {{ $product->getStockQuantity() }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Belum ada produk dari kelas ini</p>
                    @if($class->teacher_id == auth()->id() || auth()->user()->hasRole(['super_admin', 'admin']))
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Tambah Produk
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection