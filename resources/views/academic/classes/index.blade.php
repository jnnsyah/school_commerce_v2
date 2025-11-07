@extends('layouts.app')

@section('title', 'Manajemen Kelas')
@section('page-title', 'Manajemen Kelas')

@section('page-actions')
    @can('class.create')
    <a href="{{ route('classes.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Tambah Kelas
    </a>
    @endcan
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Filters -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tingkatan</label>
                        <select name="grade_id" class="form-select">
                            <option value="">Semua Tingkatan</option>
                            @foreach($grades as $grade)
                            <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>
                                {{ $grade->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jurusan</label>
                        <select name="major_id" class="form-select">
                            <option value="">Semua Jurusan</option>
                            @foreach($majors as $major)
                            <option value="{{ $major->id }}" {{ request('major_id') == $major->id ? 'selected' : '' }}>
                                {{ $major->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Classes Grid -->
        <div class="row">
            @foreach($classes as $class)
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ $class->getFullName() }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center mb-3">
                            <div class="col-6">
                                <div class="h4 text-primary">{{ $class->getStudentCount() }}</div>
                                <small class="text-muted">Siswa</small>
                            </div>
                            <div class="col-6">
                                <div class="h4 text-success">{{ $class->getActiveProductsCount() }}</div>
                                <small class="text-muted">Produk</small>
                            </div>
                        </div>
                        
                        <table class="table table-borderless small">
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
                    <div class="card-footer">
                        <div class="btn-group w-100">
                            <a href="{{ route('classes.show', $class) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            @can('class.edit')
                            <a href="{{ route('classes.edit', $class) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            @endcan
                            @can('class.delete')
                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal{{ $class->class_id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endcan
                        </div>

                        <!-- Delete Modal -->
                        @can('class.delete')
                        <div class="modal fade" id="deleteModal{{ $class->class_id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Hapus Kelas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah Anda yakin ingin menghapus kelas <strong>{{ $class->getFullName() }}</strong>?</p>
                                        <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form action="{{ route('classes.destroy', $class) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($classes->count() == 0)
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada kelas</h5>
                @can('class.create')
                <p class="text-muted">Mulai dengan menambahkan kelas pertama</p>
                <a href="{{ route('classes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>Tambah Kelas
                </a>
                @endcan
            </div>
        </div>
        @endif

        <!-- Pagination -->
        @if($classes->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $classes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection