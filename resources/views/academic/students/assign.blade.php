@extends('layouts.app')

@section('title', 'Kelola Siswa - ' . $class->getFullName())
@section('page-title', 'Kelola Siswa - ' . $class->getFullName())

@section('page-actions')
    <a href="{{ route('classes.show', $class) }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali ke Kelas
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <!-- Current Students -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-users me-2"></i>Siswa di Kelas Ini ({{ $class->students->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($class->students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NISN</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->students as $student)
                            <tr>
                                <td>
                                    {{ $student->user->name }}
                                    @if($student->is_admin_class)
                                    <span class="badge bg-success ms-1">Admin</span>
                                    @endif
                                </td>
                                <td>{{ $student->nisn }}</td>
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
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <!-- Assign New Student -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>Tambah Siswa ke Kelas
                </h5>
            </div>
            <div class="card-body">
                @if($availableStudents->count() > 0)
                <form action="{{ route('classes.students.assign', $class) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Pilih Siswa *</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">Pilih Siswa</option>
                            @foreach($availableStudents as $student)
                            <option value="{{ $student->id }}">
                                {{ $student->name }} (@{{ $student->username }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="nisn" class="form-label">NISN *</label>
                        <input type="text" class="form-control" id="nisn" name="nisn" required>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" 
                               id="is_admin_class" name="is_admin_class" value="1">
                        <label class="form-check-label" for="is_admin_class">
                            Jadikan sebagai Admin Kelas
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i>Tambah ke Kelas
                    </button>
                </form>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-user-slash fa-2x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada siswa yang tersedia untuk ditambahkan</p>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus me-1"></i>Buat User Siswa Baru
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection