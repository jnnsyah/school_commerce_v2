<!-- resources/views/auth/register.blade.php -->
@extends('layouts.guest')

@section('title', 'Daftar - School Commerce')

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
        <div class="bg-gradient-to-r from-accent to-green-600 p-6 text-white text-center">
            <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-plus text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold mb-2">Buat Akun Baru</h1>
            <p class="text-white/90 text-sm">Bergabung dengan School Commerce</p>
        </div>

        <!-- Form -->
        <div class="p-6 sm:p-8">
            <form class="space-y-4" action="{{ route('register') }}" method="POST">
                @csrf
                
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Nama Lengkap
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}"
                               required
                               class="input-mobile block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-slate-600 
                                      rounded-lg placeholder-gray-500 dark:placeholder-slate-400 
                                      focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                      bg-white dark:bg-secondary text-gray-900 dark:text-white
                                      transition duration-200"
                               placeholder="Nama lengkap">
                    </div>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Email Sekolah
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

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Nomor Telepon
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400"></i>
                        </div>
                        <input type="tel" 
                               name="no_hp" 
                               value="{{ old('no_hp') }}"
                               required
                               class="input-mobile block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-slate-600 
                                      rounded-lg placeholder-gray-500 dark:placeholder-slate-400 
                                      focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                      bg-white dark:bg-secondary text-gray-900 dark:text-white
                                      transition duration-200"
                               placeholder="08xxxxxxxxxx">
                    </div>
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               name="password" 
                               required
                               class="input-mobile block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-slate-600 
                                      rounded-lg placeholder-gray-500 dark:placeholder-slate-400 
                                      focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                      bg-white dark:bg-secondary text-gray-900 dark:text-white
                                      transition duration-200"
                               placeholder="Minimal 8 karakter">
                        <button type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePasswordVisibility('password')">
                            <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Konfirmasi Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="password" 
                               name="password_confirmation" 
                               required
                               class="input-mobile block w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-slate-600 
                                      rounded-lg placeholder-gray-500 dark:placeholder-slate-400 
                                      focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                      bg-white dark:bg-secondary text-gray-900 dark:text-white
                                      transition duration-200"
                               placeholder="Ulangi password">
                        <button type="button" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center"
                                onclick="togglePasswordVisibility('password_confirmation')">
                            <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent 
                               rounded-lg shadow-sm text-sm font-medium text-white bg-accent 
                               hover:bg-accent/90 focus:outline-none focus:ring-2 focus:ring-offset-2 
                               focus:ring-accent transition duration-200 transform hover:scale-[1.02] 
                               active:scale-[0.98]">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-slate-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" 
                       class="text-accent hover:text-accent/80 font-medium transition">
                        Masuk di sini
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(fieldName) {
    const input = document.querySelector(`input[name="${fieldName}"]`);
    const icon = input.parentNode.querySelector('button i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Mobile optimizations
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus first field on mobile
    if (window.innerWidth < 768) {
        document.querySelector('input[type="text"]')?.focus();
    }
});
</script>
@endsection