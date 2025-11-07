@extends('layouts.app')

@section('title', 'Tambah User Baru')
@section('page-title', 'Tambah User Baru')

@section('page-actions')
    <a href="{{ route('users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i>Kembali
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-plus me-2"></i>Form Tambah User
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Basic Information -->
                            <h6 class="text-primary mb-3 border-bottom pb-2">
                                <i class="fas fa-user me-2"></i>Informasi Dasar
                            </h6>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username *</label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                       id="username" name="username" value="{{ old('username') }}" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password *</label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Contact & Role Information -->
                            <h6 class="text-primary mb-3 border-bottom pb-2">
                                <i class="fas fa-address-card me-2"></i>Informasi Tambahan
                            </h6>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label">Nomor HP *</label>
                                <input type="text" class="form-control @error('no_hp') is-invalid @enderror" 
                                       id="no_hp" name="no_hp" value="{{ old('no_hp') }}" required>
                                @error('no_hp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin *</label>
                                <select class="form-select @error('gender') is-invalid @enderror" 
                                        id="gender" name="gender" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="birth_date" class="form-label">Tanggal Lahir *</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required>
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role *</label>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                        id="role" name="role" required onchange="toggleRoleFields()">
                                    <option value="">Pilih Role</option>
                                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="guru_pkwu" {{ old('role') == 'guru_pkwu' ? 'selected' : '' }}>Guru PKWU</option>
                                    <option value="wali_kelas" {{ old('role') == 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                                    <option value="guru_biasa" {{ old('role') == 'guru_biasa' ? 'selected' : '' }}>Guru Biasa</option>
                                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Siswa</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Student Fields -->
                            <div id="studentFields" style="display: {{ old('role') == 'student' ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label for="nisn" class="form-label">NISN *</label>
                                    <input type="text" class="form-control @error('nisn') is-invalid @enderror" 
                                           id="nisn" name="nisn" value="{{ old('nisn') }}">
                                    @error('nisn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="class_id" class="form-label">Kelas *</label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" 
                                            id="class_id" name="class_id">
                                        <option value="">Pilih Kelas</option>
                                        @foreach($classes as $class)
                                        <option value="{{ $class->class_id }}" 
                                                {{ old('class_id') == $class->class_id ? 'selected' : '' }}>
                                            {{ $class->getFullName() }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" 
                                           id="is_admin_class" name="is_admin_class" value="1"
                                           {{ old('is_admin_class') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_admin_class">
                                        Admin Kelas
                                    </label>
                                </div>
                            </div>

                            <!-- Teacher Fields -->
                            <div id="teacherFields" style="display: {{ in_array(old('role'), ['guru_pkwu', 'wali_kelas', 'guru_biasa']) ? 'block' : 'none' }};">
                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP *</label>
                                    <input type="text" class="form-control @error('nip') is-invalid @enderror" 
                                           id="nip" name="nip" value="{{ old('nip') }}">
                                    @error('nip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Photo Upload -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3 border-bottom pb-2">
                                <i class="fas fa-camera me-2"></i>Foto Profil
                            </h6>
                            
                            <div class="mb-3">
                                <label for="photo" class="form-label">Upload Foto</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" accept="image/*">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Simpan User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleRoleFields() {
    const role = document.getElementById('role').value;
    const studentFields = document.getElementById('studentFields');
    const teacherFields = document.getElementById('teacherFields');

    // Hide all fields first
    studentFields.style.display = 'none';
    teacherFields.style.display = 'none';

    // Show relevant fields based on role
    if (role === 'student') {
        studentFields.style.display = 'block';
    } else if (['guru_pkwu', 'wali_kelas', 'guru_biasa'].includes(role)) {
        teacherFields.style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleRoleFields();
});
</script>
@endpush