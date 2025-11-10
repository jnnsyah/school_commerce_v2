<!-- resources/views/admin/approvals/index.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Persetujuan Produk - School Commerce')
@section('page-title', 'Persetujuan Produk')
@section('page-subtitle', 'Kelola produk yang menunggu persetujuan')

@section('content')
<div class="space-y-6">
    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Menunggu</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">{{ $pendingCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Disetujui</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">{{ $approvedCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Ditolak</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">{{ $rejectedCount }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">{{ $totalCount }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex space-x-4">
                <a href="?status=pending" 
                   class="px-4 py-2 rounded-lg font-medium transition
                          {{ request('status') == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300' : 'text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                    Menunggu ({{ $pendingCount }})
                </a>
                <a href="?status=approved" 
                   class="px-4 py-2 rounded-lg font-medium transition
                          {{ request('status') == 'approved' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300' : 'text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                    Disetujui ({{ $approvedCount }})
                </a>
                <a href="?status=rejected" 
                   class="px-4 py-2 rounded-lg font-medium transition
                          {{ request('status') == 'rejected' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300' : 'text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                    Ditolak ({{ $rejectedCount }})
                </a>
                <a href="?" 
                   class="px-4 py-2 rounded-lg font-medium transition
                          {{ !request('status') ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300' : 'text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800' }}">
                    Semua ({{ $totalCount }})
                </a>
            </div>

            <div class="flex items-center space-x-2">
                <input type="text" 
                       placeholder="Cari produk..." 
                       class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm
                              focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                              bg-white dark:bg-secondary text-primary dark:text-light">
                <button class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium text-sm">
                    <i class="fas fa-search mr-2"></i>
                    Cari
                </button>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Produk
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Kelas
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Diajukan
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <!-- Product Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($product->images->count() > 0)
                                        <img class="h-10 w-10 rounded object-cover" 
                                             src="{{ asset('storage/' . $product->images->first()->file_path) }}" 
                                             alt="{{ $product->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded bg-gray-100 dark:bg-slate-700 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-primary dark:text-light">
                                        {{ $product->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-slate-400">
                                        {{ $product->category->name }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Class -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-primary dark:text-light">
                                {{ $product->class->grade->name }} {{ $product->class->major->short_name }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-slate-400">
                                {{ $product->class->section->name }}
                            </div>
                        </td>

                        <!-- Price -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-accent">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @include('components.admin.approval-badge', ['status' => $product->status])
                            @if($product->isRejected() && $product->rejection_reason)
                            <div class="text-xs text-red-600 dark:text-red-400 mt-1">
                                {{ Str::limit($product->rejection_reason, 50) }}
                            </div>
                            @endif
                        </td>

                        <!-- Created At -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                            {{ $product->created_at->format('d M Y') }}
                            <div class="text-xs text-gray-400 dark:text-slate-500">
                                {{ $product->created_at->diffForHumans() }}
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="/admin/products/{{ $product->product_id }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($product->isPending())
                                <button type="button" 
                                        onclick="showApproveModal({{ $product->product_id }}, '{{ $product->name }}')"
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition"
                                        title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>

                                <button type="button" 
                                        onclick="showRejectModal({{ $product->product_id }}, '{{ $product->name }}')"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition"
                                        title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif

                                @if($product->isRejected())
                                <button type="button" 
                                        onclick="showApproveModal({{ $product->product_id }}, '{{ $product->name }}')"
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition"
                                        title="Setujui Kembali">
                                    <i class="fas fa-redo"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-check-circle text-4xl text-gray-400 dark:text-slate-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-light mb-2">Tidak ada produk</h3>
                            <p class="text-gray-500 dark:text-slate-400">
                                @if(request('status') == 'pending')
                                    Tidak ada produk yang menunggu persetujuan
                                @elseif(request('status') == 'approved')
                                    Tidak ada produk yang disetujui
                                @elseif(request('status') == 'rejected')
                                    Tidak ada produk yang ditolak
                                @else
                                    Belum ada produk untuk ditinjau
                                @endif
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
            {{ $products->links('components.shared.pagination') }}
        </div>
        @endif
    </div>
</div>

<!-- Approve Modal -->
<div id="approve-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="hideApproveModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-secondary rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Setujui Produk</h3>
        <div class="mb-4">
            <p class="text-sm text-gray-600 dark:text-slate-400 mb-2">Anda akan menyetujui produk:</p>
            <p class="font-medium text-primary dark:text-light" id="approve-product-name"></p>
        </div>
        <form id="approve-form" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Catatan (Opsional)</label>
                <textarea name="approval_note" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500
                                 bg-white dark:bg-secondary text-primary dark:text-light"
                          placeholder="Berikan catatan persetujuan (opsional)..."></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="hideApproveModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                               hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">
                    Setujui Produk
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="reject-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="hideRejectModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-secondary rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Tolak Produk</h3>
        <div class="mb-4">
            <p class="text-sm text-gray-600 dark:text-slate-400 mb-2">Anda akan menolak produk:</p>
            <p class="font-medium text-primary dark:text-light" id="reject-product-name"></p>
        </div>
        <form id="reject-form" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Alasan Penolakan</label>
                <textarea name="rejection_reason" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500
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

<script>
function showApproveModal(productId, productName) {
    const form = document.getElementById('approve-form');
    const productNameElement = document.getElementById('approve-product-name');
    
    form.action = `/admin/product-approval/${productId}/approve`;
    productNameElement.textContent = productName;
    document.getElementById('approve-modal').classList.remove('hidden');
}

function hideApproveModal() {
    document.getElementById('approve-modal').classList.add('hidden');
}

function showRejectModal(productId, productName) {
    const form = document.getElementById('reject-form');
    const productNameElement = document.getElementById('reject-product-name');
    
    form.action = `/admin/product-approval/${productId}/reject`;
    productNameElement.textContent = productName;
    document.getElementById('reject-modal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
}

// Close modals when pressing ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        hideApproveModal();
        hideRejectModal();
    }
});
</script>
@endsection