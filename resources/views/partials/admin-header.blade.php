<!-- resources/views/partials/admin-header.blade.php -->
<header class="bg-white dark:bg-secondary border-b border-gray-200 dark:border-slate-700">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Page Title -->
        <div>
            <h1 class="text-2xl font-bold text-primary dark:text-light">
                @yield('page-title', 'Dashboard')
            </h1>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                @yield('page-subtitle', 'Selamat datang di Merchant Dashboard')
            </p>
        </div>

        <!-- Right Side -->
        <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->
            <button onclick="toggleTheme()" 
                    class="p-2 text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-300 transition">
                <i class="fas fa-moon dark:fa-sun"></i>
            </button>

            <!-- Notifications -->
            <div class="relative">
                <button class="p-2 text-gray-500 hover:text-gray-700 dark:text-slate-400 dark:hover:text-slate-300 transition">
                    <i class="fas fa-bell"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                        3
                    </span>
                </button>
            </div>

            <!-- User Menu -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center space-x-3 text-left hover:text-accent transition">
                    <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center">
                        @if(auth()->user()?->photo && file_exists(storage_path('app/public/' . auth()->user()->photo)))
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
                                alt="{{ auth()->user()->name }}"
                                class="w-8 h-8 rounded-full object-cover">
                        @else
                            <i class="fas fa-user text-accent"></i>
                        @endif
                    </div>
                    <div class="hidden md:block">
                        <p class="text-sm font-medium text-primary dark:text-light">
                            {{ auth()->user()->name ?? ''}}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-slate-400 capitalize">
                            {{ auth()->user()->getRoleName() ?? ''}}
                        </p>
                    </div>
                    <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" 
                       :class="{ 'rotate-180': open }"></i>
                </button>
                
                <!-- Dropdown Menu -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-secondary rounded-lg shadow-lg border border-gray-200 dark:border-slate-700 py-1 z-50">
                    
                    <!-- Back to Store -->
                    <a href="/user/products" 
                       class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                        <i class="fas fa-store w-4"></i>
                        <span>Kembali ke Toko</span>
                    </a>

                    <!-- Profile -->
                    <a href="/user/profile" 
                       class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                        <i class="fas fa-user w-4"></i>
                        <span>Profile Saya</span>
                    </a>

                    <!-- Settings Divider -->
                    <div class="border-t border-gray-200 dark:border-slate-700 my-1"></div>

                    <!-- Role Info -->
                    <div class="px-4 py-2">
                        <p class="text-xs text-gray-500 dark:text-slate-400">Logged in as</p>
                        <p class="text-sm font-medium text-primary dark:text-light capitalize">
                            {{ auth()->user()->getRoleName() }}
                        </p>
                    </div>

                    <!-- Logout -->
                    <form method="POST" action="/logout" class="border-t border-gray-200 dark:border-slate-700">
                        @csrf
                        <button type="submit" 
                                class="flex items-center space-x-2 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                            <i class="fas fa-sign-out-alt w-4"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
function toggleTheme() {
    const html = document.documentElement;
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        html.classList.add('light');
        localStorage.theme = 'light';
    } else {
        html.classList.remove('light');
        html.classList.add('dark');
        localStorage.theme = 'dark';
    }
}

// Initialize theme
if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.add('light');
}
</script>

<!-- Include Alpine.js for dropdown functionality -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>