<nav x-data="{ open: false }" class="bg-gray-800 text-white w-64 min-h-screen flex flex-col">
    <!-- Logo -->
    <div class="p-4 border-b border-gray-700">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
            <x-application-logo class="block h-8 w-auto text-white" />
            <span class="text-xl font-bold">Kewirausahaan</span>
        </a>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 px-4 py-6 space-y-2">
        <!-- Dashboard -->
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
        </x-sidebar-link>

        <!-- Admin Section -->
        @auth
            @php
                $user = auth()->user();
            @endphp
            
            @if($user && ($user->hasRole('super_admin') || $user->hasRole('admin')))
            <div class="pt-4">
                <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold pl-3 mb-2">Admin</h3>
                
                <x-sidebar-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    <span>Manajemen User</span>
                </x-sidebar-link>
            </div>
            @endif

            <!-- Products Section -->
            <div class="pt-4">
                <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold pl-3 mb-2">Produk</h3>
                
                @can('product.view')
                <x-sidebar-link href="#" :active="request()->routeIs('products.*')">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m8-8V4a1 1 0 00-1-1h-2a1 1 0 00-1 1v1M9 7h6"/>
                    </svg>
                    <span>Daftar Produk</span>
                </x-sidebar-link>
                @endcan

                @can('product.create')
                <x-sidebar-link href="#" :active="false">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Tambah Produk</span>
                </x-sidebar-link>
                @endcan

                @can('product.approve')
                <x-sidebar-link href="#" :active="false">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Approval Produk</span>
                </x-sidebar-link>
                @endcan
            </div>

            <!-- Orders Section -->
            <div class="pt-4">
                <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold pl-3 mb-2">Pesanan</h3>
                
                @can('order.view')
                <x-sidebar-link href="#" :active="false">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Daftar Pesanan</span>
                </x-sidebar-link>
                @endcan

                @can('order.create')
                <x-sidebar-link href="#" :active="false">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span>Buat Pesanan</span>
                </x-sidebar-link>
                @endcan

                @can('order.confirm_payment')
                <x-sidebar-link href="#" :active="false">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Konfirmasi Bayar</span>
                </x-sidebar-link>
                @endcan
            </div>

            <!-- Reports Section -->
            @canany(['report.sales', 'report.products', 'report.students'])
            <div class="pt-4">
                <h3 class="text-xs uppercase tracking-wider text-gray-400 font-semibold pl-3 mb-2">Laporan</h3>
                
                @can('report.sales')
                <x-sidebar-link href="#" :active="false">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Laporan Penjualan</span>
                </x-sidebar-link>
                @endcan

                @can('report.products')
                <x-sidebar-link href="#" :active="false">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Laporan Produk</span>
                </x-sidebar-link>
                @endcan
            </div>
            @endcanany
        @endauth
    </div>

    <!-- Mobile menu button (hidden on desktop) -->
    <div class="lg:hidden p-4 border-t border-gray-700">
        <button @click="open = !open" class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span class="ml-2">Menu</span>
        </button>
    </div>
</nav>