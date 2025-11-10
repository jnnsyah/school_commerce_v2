<!-- resources/views/user/profile/edit.blade.php -->
@extends('layouts.user-app')

@section('title', 'Edit Profile - School Commerce')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary dark:text-light mb-2">Profile Saya</h1>
        <p class="text-gray-600 dark:text-slate-400">Kelola informasi profil Anda</p>
    </div>

    <div class="max-w-2xl mx-auto">
        <!-- Profile Information -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Informasi Profil</h3>
                
                <form action="/profile" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                Nama Lengkap
                            </label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}"
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
                                Email
                            </label>
                            <input type="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                          focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                          bg-white dark:bg-secondary text-primary dark:text-light">
                            @error('email')
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

                        <!-- Role Info -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                Role
                            </label>
                            <div class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-50 dark:bg-slate-800">
                                <p class="text-sm text-gray-700 dark:text-slate-300 capitalize">
                                    {{ $user->getRoleName() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                                class="px-6 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Password Update -->
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Update Password</h3>
                
                <form action="/profile/password" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <!-- Current Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                Password Saat Ini
                            </label>
                            <input type="password" 
                                   name="current_password" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                          focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                          bg-white dark:bg-secondary text-primary dark:text-light">
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
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

                        <!-- Confirm New Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                          focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                          bg-white dark:bg-secondary text-primary dark:text-light">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6">
                        <button type="submit"
                                class="px-6 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection