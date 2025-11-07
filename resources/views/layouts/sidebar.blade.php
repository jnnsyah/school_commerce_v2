<!-- Sidebar -->
<div class="bg-light border-right" id="sidebar-wrapper">
    <div class="sidebar-heading bg-primary text-white py-4">
        <i class="fas fa-store me-2"></i>
        <strong>SekolahMart</strong>
    </div>
    <div class="list-group list-group-flush">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="list-group-item list-group-item-action bg-light {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
        </a>

        <!-- Products -->
        <a href="{{ route('products.index') }}" 
           class="list-group-item list-group-item-action bg-light {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <i class="fas fa-box me-2"></i>Produk
        </a>

        <!-- Cart -->
        <a href="{{ route('cart.index') }}" 
           class="list-group-item list-group-item-action bg-light {{ request()->routeIs('cart.*') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart me-2"></i>Keranjang
            @php
                $cartCount = \App\Models\Order\Cart::where('user_id', auth()->id())->first()?->items->count() ?? 0;
            @endphp
            @if($cartCount > 0)
                <span class="badge bg-primary rounded-pill float-end">{{ $cartCount }}</span>
            @endif
        </a>

        <!-- Orders -->
        <a href="{{ route('orders.index') }}" 
           class="list-group-item list-group-item-action bg-light {{ request()->routeIs('orders.*') ? 'active' : '' }}">
            <i class="fas fa-receipt me-2"></i>Pesanan
        </a>

        <!-- Approval Queue (Guru PKWU & Admin) -->
        @can('product.approve')
        <a href="{{ route('products.approval.index') }}" 
           class="list-group-item list-group-item-action bg-light {{ request()->routeIs('products.approval.*') ? 'active' : '' }}">
            <i class="fas fa-check-circle me-2"></i>Approval Produk
            @php
                $pendingCount = \App\Models\Product\Product::where('status_id', 1)->count();
            @endphp
            @if($pendingCount > 0)
                <span class="badge bg-warning rounded-pill float-end">{{ $pendingCount }}</span>
            @endif
        </a>
        @endcan

        <!-- User Management (Admin only) -->
        @can('user.view')
        <a href="{{ route('users.index') }}" 
           class="list-group-item list-group-item-action bg-light {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-users me-2"></i>Manajemen User
        </a>
        @endcan

        <!-- Class Management (Admin only) -->
        @can('class.view')
        <a href="{{ route('classes.index') }}" 
           class="list-group-item list-group-item-action bg-light {{ request()->routeIs('classes.*') ? 'active' : '' }}">
            <i class="fas fa-chalkboard me-2"></i>Manajemen Kelas
        </a>
        @endcan

        <!-- Inventory Management -->
        @can('product.manage')
        <a href="{{ route('inventory.stock.index') }}" 
           class="list-group-item list-group-item-action bg-light {{ request()->routeIs('inventory.*') ? 'active' : '' }}">
            <i class="fas fa-warehouse me-2"></i>Inventory
        </a>
        @endcan

        <!-- Reports -->
        @hasanyrole('super_admin|admin|guru_pkwu')
        <div class="list-group-item bg-light">
            <i class="fas fa-chart-bar me-2"></i>Laporan
        </div>
        <div class="list-group-item bg-light py-1">
            <a href="{{ route('reports.sales') }}" class="list-group-item list-group-item-action bg-light border-0 {{ request()->routeIs('reports.sales') ? 'active' : '' }}">
                <i class="fas fa-chart-line me-2"></i>Penjualan
            </a>
            <a href="{{ route('reports.products') }}" class="list-group-item list-group-item-action bg-light border-0 {{ request()->routeIs('reports.products') ? 'active' : '' }}">
                <i class="fas fa-cube me-2"></i>Produk
            </a>
            <a href="{{ route('reports.students') }}" class="list-group-item list-group-item-action bg-light border-0 {{ request()->routeIs('reports.students') ? 'active' : '' }}">
                <i class="fas fa-user-graduate me-2"></i>Siswa
            </a>
            <a href="{{ route('reports.financial') }}" class="list-group-item list-group-item-action bg-light border-0 {{ request()->routeIs('reports.financial') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave me-2"></i>Keuangan
            </a>
        </div>
        @endhasanyrole
    </div>
</div>