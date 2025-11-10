<!-- resources/views/admin/inventory/extras.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Stok Extra - School Commerce')
@section('page-title', 'Kelola Stok Extra')
@section('page-subtitle', 'Lihat dan sesuaikan stok untuk extra produk')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-primary dark:text-light">Stok Extra Produk</h2>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Total {{ $extras->total() }} extra ditemukan
            </p>
        </div>
        
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <!-- Filter Buttons -->
            <a href="{{ route('admin.inventory.extras') }}" 
               class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm 
                      {{ !request()->has('filter') ? 'bg-accent text-white border-accent' : 'text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                Semua
            </a>
            <a href="{{ route('admin.inventory.extras', ['filter' => 'low_stock']) }}" 
               class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm 
                      {{ request('filter') == 'low_stock' ? 'bg-yellow-100 text-yellow-800 border-yellow-300' : 'text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                Stok Rendah
            </a>
            <a href="{{ route('admin.inventory.extras', ['filter' => 'out_of_stock']) }}" 
               class="px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-sm 
                      {{ request('filter') == 'out_of_stock' ? 'bg-red-100 text-red-800 border-red-300' : 'text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                Stok Habis
            </a>
        </div>
    </div>

    <!-- Extras Table -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Produk & Extra
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Stok Saat Ini
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Max Qty
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($extras as $extra)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <!-- Product & Extra Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($extra->product->images->count() > 0)
                                        <img class="h-10 w-10 rounded object-cover" 
                                             src="{{ asset('storage/' . $extra->product->images->first()->file_path) }}" 
                                             alt="{{ $extra->product->name }}">
                                    @else
                                        <div class="h-10 w-10 rounded bg-gray-100 dark:bg-slate-700 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-primary dark:text-light">
                                        {{ $extra->product->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-slate-400">
                                        {{ $extra->name }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Current Stock -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-semibold 
                                {{ $extra->stock_cache <= 0 ? 'text-red-600 dark:text-red-400' : 
                                   ($extra->stock_cache <= 10 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                                {{ $extra->stock_cache }}
                            </div>
                        </td>

                        <!-- Price -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-accent">
                                Rp {{ number_format($extra->price, 0, ',', '.') }}
                            </div>
                        </td>

                        <!-- Max Quantity -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-600 dark:text-slate-400">
                                {{ $extra->max_qty ?? 'Unlimited' }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(!$extra->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-300">
                                    <i class="fas fa-pause mr-1"></i>
                                    Nonaktif
                                </span>
                            @elseif($extra->stock_cache <= 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Stok Habis
                                </span>
                            @elseif($extra->stock_cache <= 10)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Stok Rendah
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Tersedia
                                </span>
                            @endif
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button onclick="openExtraAdjustmentModal({{ $extra->id }}, '{{ $extra->product->name }} - {{ $extra->name }}', {{ $extra->stock_cache }})"
                                    class="text-accent hover:text-accent/80 transition font-medium">
                                Sesuaikan Stok
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-slate-400">
                            <i class="fas fa-plus-circle text-3xl mb-2"></i>
                            <p>Tidak ada extra ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($extras->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
            {{ $extras->links('components.shared.pagination') }}
        </div>
        @endif
    </div>
</div>

<!-- Extra Stock Adjustment Modal -->
<div id="extra-adjustment-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="closeExtraAdjustmentModal()"></div>
    
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="bg-white dark:bg-secondary rounded-lg shadow-lg border border-gray-200 dark:border-slate-700">
            <!-- Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-700">
                <h3 id="extra-modal-title" class="text-lg font-semibold text-primary dark:text-light">Sesuaikan Stok Extra</h3>
                <button onclick="closeExtraAdjustmentModal()" class="text-gray-500 hover:text-gray-700 dark:text-slate-400">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Form -->
            <form id="extra-adjustment-form" method="POST" class="p-6 space-y-4">
                @csrf
                
                <!-- Current Stock Info -->
                <div class="bg-gray-50 dark:bg-slate-800 rounded-lg p-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-600 dark:text-slate-400">Stok Saat Ini:</span>
                        <span id="extra-current-stock" class="font-semibold text-primary dark:text-light">0</span>
                    </div>
                </div>

                <!-- Adjustment Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Jenis Penyesuaian <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="adjustment_type" value="add" checked class="peer sr-only">
                            <div class="flex-1 p-3 border border-gray-300 dark:border-slate-600 rounded-lg text-center 
                                      peer-checked:border-accent peer-checked:bg-accent/10 transition">
                                <i class="fas fa-plus text-green-500 mb-1"></i>
                                <p class="text-sm font-medium">Tambah Stok</p>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="adjustment_type" value="subtract" class="peer sr-only">
                            <div class="flex-1 p-3 border border-gray-300 dark:border-slate-600 rounded-lg text-center 
                                      peer-checked:border-accent peer-checked:bg-accent/10 transition">
                                <i class="fas fa-minus text-red-500 mb-1"></i>
                                <p class="text-sm font-medium">Kurangi Stok</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Quantity -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Jumlah <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="quantity" 
                           required
                           min="1"
                           class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                  focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                  bg-white dark:bg-secondary text-primary dark:text-light"
                           placeholder="Masukkan jumlah">
                </div>

                <!-- Note -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                        Catatan
                    </label>
                    <textarea name="note" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                     focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                     bg-white dark:bg-secondary text-primary dark:text-light"
                              placeholder="Alasan penyesuaian stok (opsional)"></textarea>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" 
                            onclick="closeExtraAdjustmentModal()"
                            class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                                   hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                        Simpan Penyesuaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openExtraAdjustmentModal(extraId, extraName, currentStock) {
    const modal = document.getElementById('extra-adjustment-modal');
    const form = document.getElementById('extra-adjustment-form');
    const title = document.getElementById('extra-modal-title');
    const currentStockEl = document.getElementById('extra-current-stock');
    
    title.textContent = `Sesuaikan Stok - ${extraName}`;
    currentStockEl.textContent = currentStock;
    form.action = `/admin/inventory/extras/${extraId}/adjust`;
    
    modal.classList.remove('hidden');
}

function closeExtraAdjustmentModal() {
    document.getElementById('extra-adjustment-modal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('extra-adjustment-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeExtraAdjustmentModal();
    }
});
</script>
@endsection