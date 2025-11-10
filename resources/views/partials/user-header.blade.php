<!-- resources/views/partials/user-header.blade.php -->
<header class="bg-white dark:bg-secondary border-b border-gray-200 dark:border-slate-800 sticky top-0 z-40">
    <div class="container mx-auto px-4 py-3">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center">
                    <i class="fas fa-store text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-primary dark:text-light">
                        School<span class="text-accent">Commerce</span>
                    </h1>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-4">
                <!-- Merchant Toggle (for admin/guru_pkwu/wali_kelas) -->
                @if(auth()->check() && auth()->user()->hasRole(['admin', 'guru_pkwu', 'wali_kelas']))
                <a href="/admin/dashboard" 
                   class="hidden sm:flex items-center space-x-2 bg-accent text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-accent/90 transition">
                    <i class="fas fa-chart-line"></i>
                    <span>Merchant</span>
                </a>
                @endif

                <!-- Cart Button -->
                <button id="cart-button" class="relative p-2 text-primary dark:text-light hover:text-accent transition">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span id="cart-count" class="absolute -top-1 -right-1 bg-accent text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                        0
                    </span>
                </button>

                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center space-x-2 text-primary dark:text-light hover:text-accent transition">
                        <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center">
                            @if(auth()->user()?->photo && file_exists(storage_path('app/public/' . auth()->user()->photo)))
                                <img src="{{ asset('storage/' . auth()->user()->photo) }}" 
                                    alt="{{ auth()->user()->name }}"
                                    class="w-8 h-8 rounded-full object-cover">
                            @else
                                <i class="fas fa-user text-accent"></i>
                            @endif
                        </div>
                        <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name ?? ' '}}</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" 
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
                        <!-- Profile -->
                        <a href="/user/profile" 
                           class="flex items-center space-x-2 px-4 py-2 text-sm text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                            <i class="fas fa-user w-4"></i>
                            <span>Profile Saya</span>
                        </a>

                        <!-- Role Info -->
                        <div class="px-4 py-2 border-t border-gray-200 dark:border-slate-700">
                            <p class="text-xs text-gray-500 dark:text-slate-400">Role</p>
                            <p class="text-sm font-medium text-primary dark:text-light capitalize">
                                {{auth()->check() ? auth()->user()->getRoleName() : ''}}
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
    </div>
</header>

<!-- Include Alpine.js for dropdown functionality -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>