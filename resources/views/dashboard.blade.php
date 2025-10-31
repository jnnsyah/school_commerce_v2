<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

                    <!-- Welcome Message dengan Role -->
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <h2 class="text-xl font-semibold mb-2">Selamat datang, {{ Auth::user()->name }}!</h2>
                        <p class="text-gray-600">
                            Role: 
                            @foreach(Auth::user()->getRoleNames() as $role)
                                <span class="px-2 py-1 text-sm rounded-full 
                                    @if($role === 'super_admin') bg-red-100 text-red-800
                                    @elseif($role === 'admin') bg-purple-100 text-purple-800
                                    @elseif($role === 'guru_pkwu') bg-blue-100 text-blue-800
                                    @elseif($role === 'wali_kelas') bg-green-100 text-green-800
                                    @elseif($role === 'guru_biasa') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $role }}
                                </span>
                            @endforeach
                        </p>
                    </div>

                    <!-- Quick Actions berdasarkan Role -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        <!-- Admin Actions -->
                        @can('user.view')
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-3">Admin Panel</h3>
                            <ul class="space-y-2">
                                <li><a href="{{ route('admin.users.index') }}" class="text-indigo-600 hover:text-indigo-900">Manage Users</a></li>
                            </ul>
                        </div>
                        @endcan

                        <!-- Product Management -->
                        @can('product.view')
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-3">Produk</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-indigo-600 hover:text-indigo-900">Lihat Produk</a></li>
                                @can('product.create')
                                <li><a href="#" class="text-indigo-600 hover:text-indigo-900">Tambah Produk</a></li>
                                @endcan
                                @can('product.approve')
                                <li><a href="#" class="text-indigo-600 hover:text-indigo-900">Approve Produk</a></li>
                                @endcan
                            </ul>
                        </div>
                        @endcan

                        <!-- Order Management -->
                        @can('order.view')
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold mb-3">Pesanan</h3>
                            <ul class="space-y-2">
                                <li><a href="#" class="text-indigo-600 hover:text-indigo-900">Lihat Pesanan</a></li>
                                @can('order.create')
                                <li><a href="#" class="text-indigo-600 hover:text-indigo-900">Buat Pesanan</a></li>
                                @endcan
                                @can('order.confirm_payment')
                                <li><a href="#" class="text-indigo-600 hover:text-indigo-900">Konfirmasi Pembayaran</a></li>
                                @endcan
                            </ul>
                        </div>
                        @endcan

                    </div>

                    <!-- User Info -->
                    <div class="mt-8 bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-3">Your Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                                <p><strong>Username:</strong> {{ Auth::user()->username }}</p>
                            </div>
                            <div>
                                <p><strong>Phone:</strong> {{ Auth::user()->no_hp }}</p>
                                <p><strong>Gender:</strong> {{ Auth::user()->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                                <p><strong>Birth Date:</strong> {{ Auth::user()->birth_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>