<!-- resources/views/admin/users/edit.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Edit User - School Commerce')
@section('page-title', 'Edit Pengguna')
@section('page-subtitle', 'Update informasi pengguna')

@section('content')
<div class="max-w-2xl mx-auto">
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

                <!-- Role -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                   focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                   bg-white dark:bg-secondary text-primary dark:text-light">
                        <option value="">Pilih Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role', $user->getRoleName()) == $role->name ? 'selected' : '' }}>
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
            @if($user->student)
            <div class="border-t border-gray-200 dark:border-slate-700 pt-6">
                <h4 class="text-lg font-semibold text-primary dark:text-light mb-4">Informasi Siswa</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                            NISN
                        </label>
                        <input type="text" 
                               name="nisn" 
                               value="{{ old('nisn', $user->student->nisn) }}"
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
                                <option value="{{ $class->class_id }}" {{ old('class_id', $user->student->class_id) == $class->class_id ? 'selected' : '' }}>
                                    {{ $class->grade->name }} {{ $class->major->short_name }} {{ $class->section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @endif

            <!-- Teacher Specific Fields -->
            @if($user->teacher)
            <div class="border-t border-gray-200 dark:border-slate-700 pt-6">
                <h4 class="text-lg font-semibold text-primary dark:text-light mb-4">Informasi Guru</h4>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        NIP
                    </label>
                    <input type="text" 
                           name="nip" 
                           value="{{ old('nip', $user->teacher->nip) }}"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light">
                </div>
            </div>
            @endif

            <!-- Form Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-slate-700">
                <a href="/admin/users" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                    Batal
                </a>
                <div class="flex space-x-3">
                    <a href="/admin/users" 
                       class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                        Kembali
                    </a>
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
@endsection