<!-- resources/views/admin/users/edit.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Edit User - School Commerce')
@section('page-title', 'Edit Pengguna')
@section('page-subtitle', 'Update informasi pengguna')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
        <!-- Form Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-primary dark:text-light">Edit Pengguna</h3>
                    <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                        Update informasi {{ $user->name }}
                    </p>
                </div>
                @include('components.admin.user-role-badge', ['user' => $user])
            </div>
        </div>

        <!-- User Form -->
        <form action="/admin/users/{{ $user->id }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           value="{{ old('name', $user->name) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light">
                    @error('name')
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
                           value="{{ old('email', $user->email) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light">
                    @error('email')
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
                           value="{{ old('username', $user->username) }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light">
                    @error('username')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Nomor Telepon
                    </label>
                    <input type="tel" 
                           name="no_hp" 
                           value="{{ old('no_hp', $user->no_hp) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light">
                    @error('no_hp')
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
                           value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light">
                    @error('birth_date')
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
                        <option value="L" {{ old('gender', $user->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('gender', $user->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Role Assignment Section -->
            <div class="border-t border-gray-200 dark:border-slate-700 pt-6">
                <h4 class="text-lg font-semibold text-primary dark:text-light mb-4">Assign Role & Permissions</h4>
                
                <!-- Current Role -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-3">
                        Role Saat Ini
                    </label>
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->roles as $role)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-accent/20 text-accent border border-accent/30">
                                <i class="fas fa-user-shield mr-1"></i>
                                {{ $role->name }}
                            </span>
                        @endforeach
                        @if($user->roles->isEmpty())
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-700">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Belum ada role
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-3">
                        Pilih Role Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($roles as $role)
                            <div class="relative">
                                <input type="radio" 
                                       name="role" 
                                       value="{{ $role->name }}" 
                                       id="role-{{ $role->id }}"
                                       {{ old('role', $user->getRoleName()) == $role->name ? 'checked' : '' }}
                                       class="hidden peer">
                                <label for="role-{{ $role->id }}" 
                                       class="flex flex-col p-4 border-2 border-gray-200 dark:border-slate-600 rounded-lg 
                                              cursor-pointer transition-all hover:border-accent peer-checked:border-accent 
                                              peer-checked:bg-accent/5">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-primary dark:text-light">{{ $role->name }}</span>
                                        <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-accent 
                                                    peer-checked:bg-accent flex items-center justify-center">
                                            <i class="fas fa-check text-white text-xs peer-checked:block hidden"></i>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400">
                                        @php
                                            $permissionCount = $role->permissions->count();
                                        @endphp
                                        {{ $permissionCount }} permissions
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quick Actions -->
                <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-4">
                    <h5 class="text-sm font-medium text-gray-700 dark:text-slate-300 mb-3">Quick Actions</h5>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" 
                                onclick="setRole('student')"
                                class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition">
                            Set as Student
                        </button>
                        <button type="button" 
                                onclick="setRole('guru_biasa')"
                                class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition">
                            Set as Guru Biasa
                        </button>
                        <button type="button" 
                                onclick="setRole('wali_kelas')"
                                class="px-3 py-1 text-xs bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition">
                            Set as Wali Kelas
                        </button>
                        <button type="button" 
                                onclick="setRole('guru_pkwu')"
                                class="px-3 py-1 text-xs bg-orange-100 text-orange-700 rounded-full hover:bg-orange-200 transition">
                            Set as Guru PKWU
                        </button>
                    </div>
                </div>
            </div>

            <!-- Student Specific Fields -->
            @if($user->student || old('role') == 'student')
            <div class="border-t border-gray-200 dark:border-slate-700 pt-6">
                <h4 class="text-lg font-semibold text-primary dark:text-light mb-4">Informasi Siswa</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            NISN
                        </label>
                        <input type="text" 
                               name="nisn" 
                               value="{{ old('nisn', $user->student->nisn ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                      focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                      bg-white dark:bg-secondary text-primary dark:text-light">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            Kelas
                        </label>
                        <select name="class_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                       focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                       bg-white dark:bg-secondary text-primary dark:text-light">
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->class_id }}" {{ old('class_id', $user->student->class_id ?? '') == $class->class_id ? 'selected' : '' }}>
                                    {{ $class->grade->name }} {{ $class->major->short_name }} {{ $class->section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @endif

            <!-- Teacher Specific Fields -->
            @if($user->teacher || in_array(old('role'), ['guru_biasa', 'wali_kelas', 'guru_pkwu']))
            <div class="border-t border-gray-200 dark:border-slate-700 pt-6">
                <h4 class="text-lg font-semibold text-primary dark:text-light mb-4">Informasi Guru</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            NIP
                        </label>
                        <input type="text" 
                               name="nip" 
                               value="{{ old('nip', $user->teacher->nip ?? '') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                      focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                      bg-white dark:bg-secondary text-primary dark:text-light">
                    </div>
                    
                    <!-- Teacher Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            Tipe Guru
                        </label>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_wali_kelas" 
                                       id="is_wali_kelas"
                                       value="1"
                                       {{ old('is_wali_kelas', $user->teacher->is_wali_kelas ?? false) ? 'checked' : '' }}
                                       class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent">
                                <label for="is_wali_kelas" class="ml-2 text-sm text-gray-700 dark:text-slate-300">
                                    Wali Kelas
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_pkwu" 
                                       id="is_pkwu"
                                       value="1"
                                       {{ old('is_pkwu', $user->teacher->is_pkwu ?? false) ? 'checked' : '' }}
                                       class="w-4 h-4 text-accent border-gray-300 rounded focus:ring-accent">
                                <label for="is_pkwu" class="ml-2 text-sm text-gray-700 dark:text-slate-300">
                                    Guru PKWU
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Password Section (Optional) -->
            <div class="border-t border-gray-200 dark:border-slate-700 pt-6">
                <h4 class="text-lg font-semibold text-primary dark:text-light mb-4">Ubah Password</h4>
                <p class="text-sm text-gray-600 dark:text-slate-400 mb-4">
                    Kosongkan jika tidak ingin mengubah password
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            Password Baru
                        </label>
                        <input type="password" 
                               name="password" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                      focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                      bg-white dark:bg-secondary text-primary dark:text-light">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            Konfirmasi Password
                        </label>
                        <input type="password" 
                               name="password_confirmation" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                      focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                      bg-white dark:bg-secondary text-primary dark:text-light">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-slate-700">
                <div class="flex space-x-3">
                    <a href="/admin/users" 
                       class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <a href="/admin/users/{{ $user->id }}" 
                       class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Detail
                    </a>
                </div>
                <div class="flex space-x-3">
                    <button type="button" 
                            onclick="resetForm()"
                            class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                                   hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                        <i class="fas fa-undo mr-2"></i>
                        Reset
                    </button>
                    <button type="submit" 
                            class="px-6 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                        <i class="fas fa-save mr-2"></i>
                        Update User
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Quick role assignment
function setRole(roleName) {
    const radioButton = document.querySelector(`input[value="${roleName}"]`);
    if (radioButton) {
        radioButton.checked = true;
        radioButton.dispatchEvent(new Event('change'));
    }
}

// Show/hide fields based on role selection
document.addEventListener('DOMContentLoaded', function() {
    const roleRadios = document.querySelectorAll('input[name="role"]');
    
    function toggleRoleSpecificFields() {
        const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
        
        // Hide all role-specific sections first
        document.querySelectorAll('[data-role-section]').forEach(section => {
            section.style.display = 'none';
        });
        
        // Show relevant sections
        if (selectedRole === 'student') {
            const studentSection = document.querySelector('[data-role-section="student"]');
            if (studentSection) studentSection.style.display = 'block';
        } else if (['guru_biasa', 'wali_kelas', 'guru_pkwu'].includes(selectedRole)) {
            const teacherSection = document.querySelector('[data-role-section="teacher"]');
            if (teacherSection) teacherSection.style.display = 'block';
        }
    }
    
    roleRadios.forEach(radio => {
        radio.addEventListener('change', toggleRoleSpecificFields);
    });
    
    // Initial toggle
    toggleRoleSpecificFields();
});

// Form reset
function resetForm() {
    if (confirm('Reset semua perubahan?')) {
        document.querySelector('form').reset();
    }
}

// Real-time role description
document.addEventListener('DOMContentLoaded', function() {
    const roleDescriptions = {
        'super_admin': 'Akses penuh ke semua fitur sistem',
        'admin': 'Akses hampir penuh kecuali sistem settings',
        'guru_pkwu': 'Dapat menyetujui produk dan memantau aktivitas',
        'wali_kelas': 'Dapat membuat produk untuk kelasnya',
        'guru_biasa': 'Akses dasar untuk melihat produk dan order',
        'student': 'Dapat membeli produk dan melihat order history'
    };

    const roleRadios = document.querySelectorAll('input[name="role"]');
    const descriptionElement = document.createElement('div');
    descriptionElement.className = 'mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg text-sm text-blue-700 dark:text-blue-300';
    descriptionElement.id = 'role-description';

    function updateRoleDescription() {
        const selectedRole = document.querySelector('input[name="role"]:checked')?.value;
        if (selectedRole && roleDescriptions[selectedRole]) {
            descriptionElement.textContent = roleDescriptions[selectedRole];
            const roleContainer = document.querySelector('input[name="role"]:checked').closest('.relative');
            if (!roleContainer.querySelector('#role-description')) {
                roleContainer.appendChild(descriptionElement);
            }
        } else {
            descriptionElement.remove();
        }
    }

    roleRadios.forEach(radio => {
        radio.addEventListener('change', updateRoleDescription);
    });

    // Initial description
    updateRoleDescription();
});
</script>

<style>
input[name="role"]:checked + label {
    border-color: #00bba7;
    background-color: rgba(0, 187, 167, 0.05);
}

input[name="role"]:checked + label .fa-check {
    display: block !important;
}
</style>
@endsection