<!-- resources/views/auth/forgot-password.blade.php -->
@extends('layouts.guest')

@section('title', 'Lupa Password - School Commerce')

@section('content')
<div class="w-full max-w-md">
    <!-- Theme Toggle -->
    <div class="flex justify-end mb-6">
        <button onclick="toggleTheme()" 
                class="p-2 text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-300 transition rounded-lg">
            <i class="fas fa-moon dark:fa-sun"></i>
        </button>
    </div>

    <!-- Card -->
    <div class="bg-white dark:bg-secondary rounded-2xl shadow-lg border border-gray-200 dark:border-slate-700 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-accent to-orange-600 p-6 text-white text-center">
            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-key text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold mb-2">Reset Password</h1>
            <p class="text-white/90 text-sm">Masukkan email untuk reset password</p>
        </div>

        <!-- Form -->
        <div class="p-6 sm:p-8">
            <form class="space-y-4" action="{{ route('password.email') }}" method="POST">
                @csrf
                
                <div class="text-center mb-4">
                    <i class="fas fa-envelope-open-text text-4xl text-accent mb-3"></i>
                    <p class="text-sm text-gray-600 dark:text-slate-400">
                        Kami akan mengirim link reset password ke email Anda
                    </p>
                </div>

                <!-- Email Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               required
                               class="input-mobile block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-slate-600 
                                      rounded-lg placeholder-gray-500 dark:placeholder-slate-400 
                                      focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                      bg-white dark:bg-secondary text-gray-900 dark:text-white
                                      transition duration-200"
                               placeholder="email@sekolah.sch.id">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent 
                               rounded-lg shadow-sm text-sm font-medium text-white bg-accent 
                               hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 
                               focus:ring-accent transition duration-200 transform hover:scale-[1.02] 
                               active:scale-[0.98]">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Kirim Link Reset
                </button>
            </form>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center text-sm text-accent hover:text-accent/80 font-medium transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke halaman login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection