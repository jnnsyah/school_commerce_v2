@extends('layouts.app')

@section('title', 'Manajemen Siswa')
@section('page-title', 'Manajemen Siswa')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-graduate me-2"></i>Daftar Siswa
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Username</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                            <tr>
                                <td>
                                    <strong>{{ $student->user->name }}</strong>
                                    @if($student->is_admin_class)
                                    <span class="badge bg-success ms-1">Admin Kelas</span>
                                    @endif
                                </td>
                                <td>{{ $student->nisn }}</td>
                                <td>{{ $student->getClassName() }}</td>
                                <td>@ {{ $student->user->username }}</td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('users.show', $student->user) }}" class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('user.edit')
                                        <a href="{{ route('users.edit', $student->user) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-user-graduate fa-2x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada siswa</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection