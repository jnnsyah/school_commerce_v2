<!-- resources/views/admin/users/create.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Tambah User - School Commerce')
@section('page-title', 'Tambah User Baru')
@section('page-subtitle', 'Buat akun user baru (siswa/guru/admin)')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
        <!-- Form Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-primary dark:text-light">Informasi User</h3>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Isi data user dengan lengkap dan akurat
            </p>
        </div>

        <!-- User Form -->
        <form action="/admin/users" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light"
                           placeholder="Nama lengkap user">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="username" 
                           value="{{ old('username') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light"
                           placeholder="username">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light"
                           placeholder="email@example.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="password" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light"
                           placeholder="Minimal 8 karakter">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           name="password_confirmation" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light"
                           placeholder="Ulangi password">
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Nomor Telepon
                    </label>
                    <input type="tel" 
                           name="no_hp" 
                           value="{{ old('no_hp') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light"
                           placeholder="08xxxxxxxxxx">
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Jenis Kelamin
                    </label>
                    <select name="gender" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                   focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                   bg-white dark:bg-secondary text-primary dark:text-light">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Birth Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Tanggal Lahir
                    </label>
                    <input type="date" 
                           name="birth_date" 
                           value="{{ old('birth_date') }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light">
                    @error('birth_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" 
                            id="role-select"
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                   focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                   bg-white dark:bg-secondary text-primary dark:text-light">
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Student Specific Fields -->
            <div id="student-fields" class="hidden">
                <div class="border-t border-gray-200 dark:border-slate-700 pt-6">
                    <h4 class="text-lg font-semibold text-primary dark:text-light mb-4">Data Siswa</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NISN -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                NISN
                            </label>
                            <input type="text" 
                                   name="nisn" 
                                   value="{{ old('nisn') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                          focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                          bg-white dark:bg-secondary text-primary dark:text-light"
                                   placeholder="Nomor Induk Siswa Nasional">
                        </div>

                        <!-- Class -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                Kelas <span class="text-red-500">*</span>
                            </label>
                            <select name="class_id" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                           focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                           bg-white dark:bg-secondary text-primary dark:text-light">
                                <option value="">Pilih Kelas</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->class_id }}" {{ old('class_id') == $class->class_id ? 'selected' : '' }}>
                                        {{ $class->grade->name }} {{ $class->major->short_name }} {{ $class->section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Admin Class -->
                        <div class="md:col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_admin_class" 
                                       value="1"
                                       {{ old('is_admin_class') ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-accent focus:ring-accent">
                                <span class="ml-2 text-sm text-gray-700 dark:text-slate-300">
                                    Jadikan sebagai admin kelas
                                </span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teacher Specific Fields -->
            <div id="teacher-fields" class="hidden">
                <div class="border-t border-gray-200 dark:border-slate-700 pt-6">
                    <h4 class="text-lg font-semibold text-primary dark:text-light mb-4">Data Guru</h4>
                    <div class="grid grid-cols-1 gap-6">
                        <!-- NIP -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                NIP
                            </label>
                            <input type="text" 
                                   name="nip" 
                                   value="{{ old('nip') }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                          focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                          bg-white dark:bg-secondary text-primary dark:text-light"
                                   placeholder="Nomor Induk Pegawai">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-slate-700">
                <a href="/admin/users" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                    <i class="fas fa-save mr-2"></i>
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('role-select').addEventListener('change', function() {
    const role = this.value;
    const studentFields = document.getElementById('student-fields');
    const teacherFields = document.getElementById('teacher-fields');

    // Hide all fields first
    studentFields.classList.add('hidden');
    teacherFields.classList.add('hidden');

    // Show relevant fields based on role
    if (role === 'student') {
        studentFields.classList.remove('hidden');
    } else if (['guru_pkwu', 'wali_kelas', 'guru_biasa'].includes(role)) {
        teacherFields.classList.remove('hidden');
    }
});

// Trigger change on page load to show/hide fields based on existing selection
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('role-select').dispatchEvent(new Event('change'));
});
</script>
@endsection