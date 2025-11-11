<!-- resources/views/auth/login.blade.php -->
@extends('layouts.guest')

@section('title', 'Login - School Commerce')

@section('content')
<div class="w-full max-w-md">
    <!-- Theme Toggle for Auth Pages -->
    <div class="flex justify-end mb-6">
        <button onclick="toggleTheme()" 
                class="p-2 text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-300 transition rounded-lg">
            <i class="fas fa-moon dark:fa-sun"></i>
        </button>
    </div>

    <!-- Card -->
    <div class="bg-white dark:bg-secondary rounded-2xl shadow-lg border border-gray-200 dark:border-slate-700 overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-accent to-blue-600 p-6 text-white text-center">
            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-store text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold mb-2">School<span class="text-white">Commerce</span></h1>
            <p class="text-white/90 text-sm">Platform Kewirausahaan Sekolah</p>
        </div>

        <!-- Form Section -->
        <div class="p-6 sm:p-8">
            <h2 class="text-xl font-bold text-center text-primary dark:text-light mb-2">
                Masuk ke Akun
            </h2>
            <p class="text-center text-gray-600 dark:text-slate-400 text-sm mb-6">
                Masukkan kredensial Anda untuk melanjutkan
            </p>

            <form class="space-y-4" action="{{ route('login') }}" method="POST">
                @csrf
                
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="login" 
                               name="login" 
                               type="text" 
                               autocomplete="login"
                               required
                               value="{{ old('login') }}"
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

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               autocomplete="current-password"
                               required
                               class="input-mobile block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-slate-600 
                                      rounded-lg placeholder-gray-500 dark:placeholder-slate-400 
                                      focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                      bg-white dark:bg-secondary text-gray-900 dark:text-white
                                      transition duration-200"
                               placeholder="Masukkan password">
                        <button type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePasswordVisibility()">
                            <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    @error('password')
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
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
            </form>

            <!-- Register Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-slate-400">
                    Belum punya akun?
                    <a href="{{ route('register') }}" 
                       class="text-accent hover:text-accent/80 font-medium transition">
                        Daftar di sini
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Demo Credentials Info -->
    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
        <p class="text-sm text-blue-700 dark:text-blue-300 text-center">
            <strong>Demo Access:</strong><br>
            <span class="text-xs">Admin: admin / password123</span><br>
            <span class="text-xs">Student: budi123 / password123</span>
            <span class="text-xs">Guru PKWU: gurupkwu / password123</span>
        </p>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const icon = document.querySelector('#password + .flex button i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Enhanced mobile experience
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus email field on mobile
    if (window.innerWidth < 768) {
        document.getElementById('email')?.focus();
    }
    
    // Prevent zoom on input focus for iOS
    const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.style.fontSize = '16px';
        });
    });
});
</script>
@endsection