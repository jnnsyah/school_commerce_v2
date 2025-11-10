<!-- resources/views/auth/reset-password.blade.php -->
@extends('layouts.user-app')

@section('title', 'Reset Password - School Commerce')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-primary py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div>
            <div class="flex justify-center">
                <div class="w-16 h-16 rounded-full bg-accent flex items-center justify-center">
                    <i class="fas fa-key text-white text-2xl"></i>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-primary dark:text-light">
                Reset Password
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600 dark:text-slate-400">
                Buat password baru untuk akun Anda
            </p>
        </div>

        <!-- Reset Password Form -->
        <form class="mt-8 space-y-6" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                    Alamat Email
                </label>
                <input id="email" name="email" type="email" autocomplete="email" required 
                       class="relative block w-full px-3 py-3 border border-gray-300 dark:border-slate-600 
                              placeholder-gray-500 dark:placeholder-slate-400 text-gray-900 dark:text-white 
                              rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                              bg-white dark:bg-secondary transition"
                       value="{{ old('email', $request->email) }}"
                       readonly>
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                    Password Baru
                </label>
                <input id="password" name="password" type="password" autocomplete="new-password" required 
                       class="relative block w-full px-3 py-3 border border-gray-300 dark:border-slate-600 
                              placeholder-gray-500 dark:placeholder-slate-400 text-gray-900 dark:text-white 
                              rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                              bg-white dark:bg-secondary transition"
                       placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm New Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                    Konfirmasi Password Baru
                </label>
                <input id="password_confirmation" name="password_confirmation" type="password" 
                       autocomplete="new-password" required 
                       class="relative block w-full px-3 py-3 border border-gray-300 dark:border-slate-600 
                              placeholder-gray-500 dark:placeholder-slate-400 text-gray-900 dark:text-white 
                              rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                              bg-white dark:bg-secondary transition"
                       placeholder="Ulangi password baru">
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent 
                               text-sm font-medium rounded-lg text-white bg-accent hover:bg-accent/90 
                               focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent 
                               transition transform hover:scale-[1.02]">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sync-alt text-accent/50 group-hover:text-accent/70"></i>
                    </span>
                    Reset Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection