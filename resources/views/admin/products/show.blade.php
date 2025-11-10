<!-- resources/views/admin/products/show.blade.php -->
@extends('layouts.admin-app')

@section('title', $product->name . ' - School Commerce')
@section('page-title', 'Detail Produk')
@section('page-subtitle', 'Informasi lengkap produk')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    @if($product->images->count() > 0)
                        <img class="w-16 h-16 rounded-lg object-cover" 
                             src="{{ asset('storage/' . $product->images->first()->file_path) }}" 
                             alt="{{ $product->name }}">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-gray-100 dark:bg-slate-800 flex items-center justify-center">
                            <i class="fas fa-image text-gray-400 text-xl"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-primary dark:text-light">{{ $product->name }}</h1>
                        <div class="flex items-center space-x-4 mt-1">
                            @include('components.admin.approval-badge', ['status' => $product->status])
                            <span class="text-sm text-gray-600 dark:text-slate-400">
                                SKU: {{ $product->sku }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="/admin/products" 
                       class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                              hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                        Kembali
                    </a>
                    @if($product->class->teacher_id == auth()->id() || auth()->user()->hasRole(['super_admin', 'admin']))
                    <a href="/admin/products/{{ $product->product_id }}/edit" 
                       class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column - Images & Basic Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Product Images -->
                    <div>
                        <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Gambar Produk</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @forelse($product->images as $image)
                            <div class="aspect-square rounded-lg overflow-hidden border border-gray-200 dark:border-slate-600">
                                <img src="{{ asset('storage/' . $image->file_path) }}" 
                                     alt="Product Image" 
                                     class="w-full h-full object-cover hover:scale-105 transition duration-300">
                            </div>
                            @empty
                            <div class="col-span-3 aspect-video bg-gray-100 dark:bg-slate-800 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <i class="fas fa-image text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-gray-500 dark:text-slate-400">Tidak ada gambar</p>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-primary dark:text-light mb-3">Deskripsi</h3>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-slate-300 leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>
                    </div>

                    <!-- Variants -->
                    @if($product->variants->count() > 0)
                    <div>
                        <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Varian Produk</h3>
                        <div class="overflow-hidden border border-gray-200 dark:border-slate-600 rounded-lg">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-slate-800">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-slate-300">Nama Varian</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-slate-300">Harga</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-slate-300">Stok</th>
                                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-slate-300">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                                    @foreach($product->variants as $variant)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                                        <td class="px-4 py-3 text-sm text-primary dark:text-light">{{ $variant->name }}</td>
                                        <td class="px-4 py-3 text-sm font-semibold text-accent">
                                            Rp {{ number_format($variant->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-slate-300">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $variant->stock_at > 10 ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 
                                                   ($variant->stock_at > 0 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300' : 
                                                   'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300') }}">
                                                {{ $variant->stock_at }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $variant->status_id == 1 ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 
                                                   'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300' }}">
                                                {{ $variant->status_id == 1 ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Column - Sidebar -->
                <div class="space-y-6">
                    <!-- Basic Information -->
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Informasi</h3>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm text-gray-600 dark:text-slate-400">Kategori</span>
                                <p class="text-sm font-medium text-primary dark:text-light">{{ $product->category->name }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600 dark:text-slate-400">Harga Dasar</span>
                                <p class="text-lg font-bold text-accent">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600 dark:text-slate-400">Kelas Penjual</span>
                                <p class="text-sm font-medium text-primary dark:text-light">
                                    {{ $product->class->grade->name }} {{ $product->class->major->short_name }} {{ $product->class->section->name }}
                                </p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600 dark:text-slate-400">Wali Kelas</span>
                                <p class="text-sm font-medium text-primary dark:text-light">{{ $product->class->teacher->name }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600 dark:text-slate-400">Dibuat</span>
                                <p class="text-sm font-medium text-primary dark:text-light">{{ $product->created_at->format('d M Y H:i') }}</p>
                            </div>
                            @if($product->isApproved() && $product->approved_at)
                            <div>
                                <span class="text-sm text-gray-600 dark:text-slate-400">Disetujui</span>
                                <p class="text-sm font-medium text-primary dark:text-light">{{ $product->approved_at->format('d M Y H:i') }}</p>
                                <p class="text-xs text-gray-500 dark:text-slate-500">Oleh: {{ $product->approvedBy->name ?? 'System' }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Statistik</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-slate-400">Total Terjual</span>
                                <span class="text-sm font-medium text-primary dark:text-light">{{ $product->getTotalSold() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-slate-400">Total Stok</span>
                                <span class="text-sm font-medium text-primary dark:text-light">{{ $product->getStockQuantity() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-slate-400">Pendapatan</span>
                                <span class="text-sm font-medium text-accent">Rp {{ number_format($product->orderItems()->sum('subtotal'), 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Approval Actions (for guru_pkwu) -->
                    @if(auth()->user()->hasRole('guru_pkwu') && $product->isPending())
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300 mb-3">Persetujuan</h3>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400 mb-4">
                            Produk ini menunggu persetujuan Anda
                        </p>
                        <div class="flex space-x-2">
                            <form action="/admin/approvals/{{ $product->product_id }}/approve" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" 
                                        class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium text-sm">
                                    Setujui
                                </button>
                            </form>
                            <button type="button" 
                                    onclick="showRejectModal()"
                                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium text-sm">
                                Tolak
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if(auth()->user()->hasRole('guru_pkwu') && $product->isPending())
<div id="reject-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="hideRejectModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-secondary rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Tolak Produk</h3>
        <form action="/admin/approvals/{{ $product->product_id }}/reject" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Alasan Penolakan</label>
                <textarea name="rejection_reason" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                 bg-white dark:bg-secondary text-primary dark:text-light"
                          placeholder="Berikan alasan penolakan..." required></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="hideRejectModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                               hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">
                    Tolak Produk
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function showRejectModal() {
    document.getElementById('reject-modal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
}
</script>
@endsection