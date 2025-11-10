<!-- resources/views/partials/admin-sidebar.blade.php -->
<aside id="sidebar" class="sidebar w-64 bg-white dark:bg-secondary border-r border-gray-200 dark:border-slate-700 flex flex-col">
    <!-- Logo -->
    <div class="p-4 border-b border-gray-200 dark:border-slate-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center">
                <i class="fas fa-store text-white text-lg"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-primary dark:text-light">
                    School<span class="text-accent">Commerce</span>
                </h1>
                <p class="text-xs text-gray-500 dark:text-slate-400">Merchant Dashboard</p>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="p-4 border-b border-gray-200 dark:border-slate-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center">
                <i class="fas fa-user text-accent"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-primary dark:text-light truncate">
                    {{ auth()->user()->name }}
                </p>
                <p class="text-xs text-gray-500 dark:text-slate-400 capitalize">
                    {{ auth()->user()->getRoleName() }}
                </p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2">
        <!-- Dashboard -->
        <a href="/admin/dashboard" 
           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->is('admin/dashboard') ? 'bg-accent text-white' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
            <i class="fas fa-tachometer-alt w-5"></i>
            <span>Dashboard</span>
        </a>

        <!-- Payments -->
        <a href="/admin/payments" 
        class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition
                {{ request()->is('admin/payments*') ? 'bg-accent text-white' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
            <i class="fas fa-credit-card w-5"></i>
            <span>Pembayaran</span>
            @php
                $pendingCashCount = \App\Models\Payment\Payment::where('method_id', 1)
                    ->where('status_id', 1)
                    ->count();
            @endphp
            @if($pendingCashCount > 0)
            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                {{ $pendingCashCount }}
            </span>
            @endif
        </a>

        <!-- Products -->
        <a href="/admin/products" 
           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->is('admin/products*') ? 'bg-accent text-white' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
            <i class="fas fa-box w-5"></i>
            <span>Produk</span>
        </a>

        <!-- Categories -->
        @if(auth()->user()->hasRole(['super_admin', 'admin', 'guru_pkwu']))
        <a href="/admin/categories" 
        class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition
                {{ request()->is('admin/categories*') ? 'bg-accent text-white' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
            <i class="fas fa-tags w-5"></i>
            <span>Kategori</span>
        </a>
        @endif

        <!-- Orders -->
        <a href="/admin/orders" 
           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->is('admin/orders*') ? 'bg-accent text-white' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
            <i class="fas fa-shopping-cart w-5"></i>
            <span>Pesanan</span>
        </a>

        <!-- Inventory -->
        <a href="/admin/inventory" 
           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->is('admin/inventory*') ? 'bg-accent text-white' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
            <i class="fas fa-warehouse w-5"></i>
            <span>Stok</span>
        </a>

        <!-- Approvals (for guru_pkwu) -->
        @if(auth()->user()->hasRole('guru_pkwu'))
        <a href="/admin/product-approval" 
           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->is('admin/approvals*') ? 'bg-accent text-white' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
            <i class="fas fa-check-circle w-5"></i>
            <span>Persetujuan</span>
            @php
                $pendingCount = \App\Models\Product\Product::where('status_id', \App\Models\Product\ProductStatus::PENDING)->count();
            @endphp
            @if($pendingCount > 0)
            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                {{ $pendingCount }}
            </span>
            @endif
        </a>
        @endif

        <!-- Users (for admin) -->
        @if(auth()->user()->hasRole(['super_admin', 'admin']))
        <a href="/admin/users" 
           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->is('admin/users*') ? 'bg-accent text-white' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
            <i class="fas fa-users w-5"></i>
            <span>Pengguna</span>
        </a>
        @endif

        <!-- Classes (for admin & wali_kelas) -->
        @if(auth()->user()->hasRole(['super_admin', 'admin', 'wali_kelas']))
        <a href="/admin/classes" 
           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->is('admin/classes*') ? 'bg-accent text-white' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
            <i class="fas fa-school w-5"></i>
            <span>Kelas</span>
        </a>
        @endif

        <!-- Reports -->
        <a href="/admin/reports/sales" 
           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium transition
                  {{ request()->is('admin/reports*') ? 'bg-accent text-white' : 'text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
            <i class="fas fa-chart-bar w-5"></i>
            <span>Laporan</span>
        </a>
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-200 dark:border-slate-700">
        <a href="/user/products" 
           class="flex items-center space-x-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
            <i class="fas fa-store w-5"></i>
            <span>Kembali ke Toko</span>
        </a>
        
        <form method="POST" action="/logout" class="mt-2">
            @csrf
            <button type="submit" 
                    class="flex items-center space-x-3 w-full px-3 py-2 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                <i class="fas fa-sign-out-alt w-5"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>