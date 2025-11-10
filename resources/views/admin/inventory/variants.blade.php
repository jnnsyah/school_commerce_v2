<!-- resources/views/admin/inventory/variants.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Stok Varian - School Commerce')
@section('page-title', 'Kelola Stok Varian')
@section('page-subtitle', 'Lihat dan sesuaikan stok untuk setiap varian produk')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-primary dark:text-light">Stok Varian Produk</h2>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Total {{ $variants->total() }} varian ditemukan
            </p>
        </div>
        
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <!-- Filter Buttons -->
            <a href="{{ route('admin.inventory.variants') }}" 
               class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm 
                      {{ !request()->has('filter') ? 'bg-accent text-white border-accent' : 'text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                Semua
            </a>
            <a href="{{ route('admin.inventory.variants', ['filter' => 'low_stock']) }}" 
               class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm 
                      {{ request('filter') == 'low_stock' ? 'bg-yellow-100 text-yellow-800 border-yellow-300' : 'text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                Stok Rendah
            </a>
            <a href="{{ route('admin.inventory.variants', ['filter' => 'out_of_stock']) }}" 
               class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm 
                      {{ request('filter') == 'out_of_stock' ? 'bg-red-100 text-red-800 border-red-300' : 'text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                Stok Habis
            </a>
        </div>
    </div>

    <!-- Variants Table -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Produk & Varian
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Stok Saat Ini
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Status Stok
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($variants as $variant)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <!-- Product & Variant Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($variant->product->images->count() > 0)
                                        <img class="h-10 w-10 rounded object-cover" 
                                             src="{{ asset('storage/' . $variant->product->images->first()->file_path) }}" 
                                             alt="{{ $variant->product->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded bg-gray-100 dark:bg-slate-700 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-primary dark:text-light">
                                        {{ $variant->product->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-slate-400">
                                        {{ $variant->name }}
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-slate-500">
                                        {{ $variant->product->class->grade->name }} {{ $variant->product->class->major->short_name }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Current Stock -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-semibold 
                                {{ $variant->stock_at <= 0 ? 'text-red-600 dark:text-red-400' : 
                                   ($variant->stock_at <= 10 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                                {{ $variant->stock_at }}
                            </div>
                        </td>

                        <!-- Price -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-accent">
                                Rp {{ number_format($variant->price, 0, ',', '.') }}
                            </div>
                        </td>

                        <!-- Stock Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($variant->stock_at <= 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Stok Habis
                                </span>
                            @elseif($variant->stock_at <= 10)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Stok Rendah
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Stok Tersedia
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="openAdjustmentModal({{ $variant->id }}, '{{ $variant->product->name }} - {{ $variant->name }}', {{ $variant->stock_at }})"
                                    class="text-accent hover:text-accent/80 transition font-medium">
                                Sesuaikan Stok
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">
                            <i class="fas fa-layer-group text-3xl mb-2"></i>
                            <p>Tidak ada varian ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($variants->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
            {{ $variants->links('components.shared.pagination') }}
        </div>
        @endif
    </div>
</div>

<!-- Stock Adjustment Modal -->
@include('admin.inventory.partials.stock-adjustment-modal')

<script>
function openAdjustmentModal(variantId, variantName, currentStock) {
    const modal = document.getElementById('stock-adjustment-modal');
    const form = document.getElementById('stock-adjustment-form');
    const title = document.getElementById('modal-title');
    const currentStockEl = document.getElementById('current-stock');
    
    title.textContent = `Sesuaikan Stok - ${variantName}`;
    currentStockEl.textContent = currentStock;
    form.action = `/admin/inventory/variants/${variantId}/adjust`;
    
    modal.classList.remove('hidden');
}

function closeAdjustmentModal() {
    document.getElementById('stock-adjustment-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('stock-adjustment-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAdjustmentModal();
    }
});
</script>
@endsection