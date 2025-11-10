<!-- resources/views/admin/orders/index.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Kelola Pesanan - School Commerce')
@section('page-title', 'Kelola Pesanan')
@section('page-subtitle', 'Daftar semua pesanan')

@section('content')
<div class="space-y-6">
    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Pending</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">{{ $statusCounts['pending'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Dibayar</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">{{ $statusCounts['paid'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-cogs text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Diproses</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">{{ $statusCounts['processing'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-flag-checkered text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Selesai</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">{{ $statusCounts['completed'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times text-white text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Dibatalkan</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">{{ $statusCounts['cancelled'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-4">
        <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                           focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                           bg-white dark:bg-secondary text-primary dark:text-light">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->status_id }}" {{ request('status') == $status->status_id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Dari Tanggal</label>
                <input type="date" 
                       name="date_from" 
                       value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                              focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                              bg-white dark:bg-secondary text-primary dark:text-light">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Sampai Tanggal</label>
                <input type="date" 
                       name="date_to" 
                       value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                              focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                              bg-white dark:bg-secondary text-primary dark:text-light">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-end space-x-2">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                    <i class="fas fa-filter mr-2"></i>
                    Filter
                </button>
                <a href="{{ url()->current() }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                          hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                    <i class="fas fa-refresh"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Pesanan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Items
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <!-- Order Info -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-primary dark:text-light">
                                #{{ $order->invoice_number }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-slate-400">
                                {{ $order->getItemsCount() }} items
                            </div>
                        </td>

                        <!-- Customer -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-primary dark:text-light">
                                {{ $order->user->name }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-slate-400">
                                {{ $order->user->email }}
                            </div>
                        </td>

                        <!-- Items -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-primary dark:text-light space-y-1">
                                @foreach($order->items->take(2) as $item)
                                <div class="flex items-center space-x-2">
                                    @if($item->product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $item->product->images->first()->file_path) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="w-6 h-6 rounded object-cover">
                                    @endif
                                    <span class="truncate max-w-xs">{{ $item->product->name }}</span>
                                    <span class="text-gray-500">x{{ $item->qty }}</span>
                                </div>
                                @endforeach
                                @if($order->items->count() > 2)
                                <div class="text-xs text-gray-500">
                                    +{{ $order->items->count() - 2 }} items lainnya
                                </div>
                                @endif
                            </div>
                        </td>

                        <!-- Total -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-accent">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($order->status_id == 1) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                                @elseif($order->status_id == 2) bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                @elseif($order->status_id == 3) bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300
                                @elseif($order->status_id == 4) bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                @else bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 @endif">
                                {{ $order->status->name }}
                            </span>
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                            {{ $order->created_at->format('d M Y') }}
                            <div class="text-xs text-gray-400 dark:text-slate-500">
                                {{ $order->created_at->format('H:i') }}
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="/admin/orders/{{ $order->order_id }}" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition"
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if(in_array($order->status_id, [1, 2])) <!-- Pending or Paid -->
                                <button type="button" 
                                        onclick="showStatusModal({{ $order->order_id }})"
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition"
                                        title="Update Status">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endif

                                @if($order->status_id == 1) <!-- Pending -->
                                <form action="/admin/orders/{{ $order->order_id }}/cancel" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition"
                                            title="Batalkan"
                                            onclick="return confirm('Batalkan pesanan ini?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-shopping-cart text-4xl text-gray-400 dark:text-slate-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-light mb-2">Tidak ada pesanan</h3>
                            <p class="text-gray-500 dark:text-slate-400">Belum ada pesanan yang dibuat</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
            {{ $orders->links('components.shared.pagination') }}
        </div>
        @endif
    </div>
</div>

<!-- Status Update Modal -->
<div id="status-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="hideStatusModal()"></div>
    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-secondary rounded-lg p-6 w-96">
        <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Update Status Pesanan</h3>
        <form id="status-form" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Status Baru</label>
                <select name="status_id" 
                        class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                               focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                               bg-white dark:bg-secondary text-primary dark:text-light" required>
                    <option value="">Pilih Status</option>
                    @foreach($statuses as $status)
                        @if($status->status_id > 1) <!-- Exclude pending -->
                        <option value="{{ $status->status_id }}">{{ $status->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Catatan (Opsional)</label>
                <textarea name="note" 
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                 focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                 bg-white dark:bg-secondary text-primary dark:text-light"
                          placeholder="Tambahkan catatan untuk customer..."></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="hideStatusModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                               hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
                    Batal
                </button>
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition font-medium">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showStatusModal(orderId) {
    const form = document.getElementById('status-form');
    form.action = `/admin/orders/${orderId}/status`;
    document.getElementById('status-modal').classList.remove('hidden');
}

function hideStatusModal() {
    document.getElementById('status-modal').classList.add('hidden');
}
</script>
@endsection