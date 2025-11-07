@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('page-actions')
    @can('user.create')
    <a href="{{ route('users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i>Tambah User
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
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="">Semua Role</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tipe User</label>
                        <select name="type" class="form-select">
                            <option value="">Semua Tipe</option>
                            <option value="student" {{ request('type') == 'student' ? 'selected' : '' }}>Siswa</option>
                            <option value="teacher" {{ request('type') == 'teacher' ? 'selected' : '' }}>Guru</option>
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

        <!-- Users Table -->
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-primary">
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Kontak</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($user->photo)
                                        <img src="{{ asset('storage/' . $user->photo) }}" 
                                             alt="{{ $user->name }}" 
                                             class="rounded-circle me-3" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        @endif
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            <br>
                                            <small class="text-muted">@ {{ $user->username }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @include('users.partials.role-badge', ['user' => $user])
                                    @if($user->student)
                                    <br>
                                    <small class="text-muted">Kelas: {{ $user->student->getClassName() }}</small>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $user->email }}</small>
                                    <br>
                                    <small class="text-muted">{{ $user->no_hp }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-success">Aktif</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('user.edit')
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endcan
                                        @can('user.delete')
                                        @if($user->id !== auth()->id())
                                        <button type="button" class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $user->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                        @endcan
                                    </div>

                                    <!-- Delete Modal -->
                                    @can('user.delete')
                                    @if($user->id !== auth()->id())
                                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Hapus User</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>?</p>
                                                    <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endcan
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection