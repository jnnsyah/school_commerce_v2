<!-- resources/views/admin/orders/show.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Detail Pesanan - School Commerce')
@section('page-title', 'Detail Pesanan')
@section('page-subtitle', 'Kelola dan lacak pesanan #' . $order->invoice_number)

@section('content')
<div class="space-y-6">
    <!-- Order Header -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
        <div class="p-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-primary dark:text-light mb-2">
                        Pesanan #{{ $order->invoice_number }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600 dark:text-slate-400">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $order->created_at->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-clock"></i>
                            <span>{{ $order->created_at->format('H:i') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-user"></i>
                            <span>{{ $order->user->name }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 lg:mt-0 flex items-center space-x-4">
                    <!-- Status Badge -->
                    <span class="px-4 py-2 rounded-full text-sm font-medium 
                        @if($order->status_id == 1) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                        @elseif($order->status_id == 2) bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                        @elseif($order->status_id == 3) bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300
                        @elseif($order->status_id == 4) bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                        @else bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 @endif">
                        {{ $order->status->name }}
                    </span>
                    
                    <!-- Total Amount -->
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-slate-400">Total</p>
                        <p class="text-xl font-bold text-accent">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Order Items & Timeline -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Item Pesanan</h3>
                    
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                        <div class="flex items-start space-x-4 p-4 rounded-lg border border-gray-200 dark:border-slate-600">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                @if($item->product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $item->product->images->first()->file_path) }}" 
                                         alt="{{ $item->product->name }}"
                                         class="w-16 h-16 rounded-lg object-cover">
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-gray-100 dark:bg-slate-800 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Product Info -->
                            <div class="flex-1">
                                <h4 class="font-medium text-primary dark:text-light">
                                    {{ $item->product->name }}
                                </h4>
                                
                                @if($item->variant)
                                <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                                    Variant: {{ $item->variant->name }}
                                </p>
                                @endif
                                
                                <!-- Extras -->
                                @if($item->extras->count() > 0)
                                <div class="mt-2">
                                    <p class="text-xs text-gray-500 dark:text-slate-500 mb-1">Extras:</p>
                                    <div class="space-y-1">
                                        @foreach($item->extras as $extra)
                                        <div class="flex justify-between text-xs">
                                            <span>{{ $extra->extra->name }} ({{ $extra->qty }}x)</span>
                                            <span>Rp {{ number_format($extra->price * $extra->qty, 0, ',', '.') }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Price & Quantity -->
                            <div class="text-right">
                                <p class="text-sm text-gray-600 dark:text-slate-400">
                                    {{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                                <p class="font-semibold text-primary dark:text-light mt-1">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-slate-500 mt-1">
                                    Kelas: {{ $item->product->class->grade->name }} {{ $item->product->class->major->short_name }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                        <div class="flex justify-between items-center text-lg font-semibold text-primary dark:text-light">
                            <span>Total Pesanan</span>
                            <span class="text-accent">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Riwayat Status</h3>
                    
                    <div class="space-y-4">
                        @forelse($order->transactionLogs as $log)
                        <div class="flex items-start space-x-4">
                            <!-- Timeline dot -->
                            <div class="flex-shrink-0 w-3 h-3 mt-2 rounded-full bg-accent"></div>
                            
                            <!-- Content -->
                            <div class="flex-1 pb-4 border-b border-gray-200 dark:border-slate-700 last:border-b-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-medium text-primary dark:text-light">
                                            Status diubah menjadi <span class="text-accent">{{ $log->status->name }}</span>
                                        </p>
                                        @if($log->note)
                                        <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                                            Catatan: {{ $log->note }}
                                        </p>
                                        @endif
                                    </div>
                                    <div class="text-right text-sm text-gray-500 dark:text-slate-500">
                                        <p>{{ $log->created_at->translatedFormat('d M Y') }}</p>
                                        <p>{{ $log->created_at->format('H:i') }}</p>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-slate-500 mt-2">
                                    Oleh: {{ $log->createdBy->name ?? 'System' }}
                                </p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-500 dark:text-slate-400">
                            <i class="fas fa-history text-3xl mb-2"></i>
                            <p>Belum ada riwayat status</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Actions & Info -->
        <div class="space-y-6">
            <!-- Order Actions -->
            <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Kelola Pesanan</h3>
                    
                    <!-- Status Update Form -->
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                Ubah Status
                            </label>
                            <select name="status_id" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                           focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                           bg-white dark:bg-secondary text-primary dark:text-light">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->status_id }}" 
                                            {{ $order->status_id == $status->status_id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">
                                Catatan (Opsional)
                            </label>
                            <textarea name="note" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                             focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                             bg-white dark:bg-secondary text-primary dark:text-light"
                                      placeholder="Tambahkan catatan untuk perubahan status..."></textarea>
                        </div>
                        
                        <button type="submit" 
                                class="w-full bg-accent text-white py-2 rounded-lg hover:bg-accent/90 transition font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Update Status
                        </button>
                    </form>
                    
                    <!-- Additional Actions -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-slate-700 space-y-2">
                        @if($order->canBeCancelled())
                        <form action="{{ route('admin.orders.cancel', $order) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')"
                                    class="w-full text-left px-3 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition text-sm">
                                <i class="fas fa-times-circle mr-2"></i>
                                Batalkan Pesanan
                            </button>
                        </form>
                        @endif
                        
                        @if($order->isCompleted())
                        <button class="w-full text-left px-3 py-2 text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition text-sm">
                            <i class="fas fa-print mr-2"></i>
                            Cetak Invoice
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Informasi Pelanggan</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Nama</p>
                            <p class="font-medium text-primary dark:text-light">{{ $order->user->name }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Email</p>
                            <p class="font-medium text-primary dark:text-light">{{ $order->user->email }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Telepon</p>
                            <p class="font-medium text-primary dark:text-light">{{ $order->user->no_hp ?? '-' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Role</p>
                            <p class="font-medium text-primary dark:text-light capitalize">{{ $order->user->getRoleName() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Information -->
            <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Informasi Pesanan</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-slate-400">ID Pesanan</p>
                            <p class="font-medium text-primary dark:text-light">#{{ $order->invoice_number }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Tanggal Pesanan</p>
                            <p class="font-medium text-primary dark:text-light">
                                {{ $order->created_at->translatedFormat('d F Y, H:i') }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Terakhir Diupdate</p>
                            <p class="font-medium text-primary dark:text-light">
                                {{ $order->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        
                        @if($order->completed_at)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-slate-400">Selesai Pada</p>
                            <p class="font-medium text-primary dark:text-light">
                                {{ $order->completed_at->translatedFormat('d F Y, H:i') }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Notes -->
            @if($order->customer_notes)
            <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Catatan Pelanggan</h3>
                    <p class="text-sm text-gray-600 dark:text-slate-400 italic">
                        "{{ $order->customer_notes }}"
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Navigation -->
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.orders.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 rounded-lg text-primary dark:text-light 
                  hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pesanan
        </a>
        
        <div class="flex space-x-3">
            @if($order->payments->count() > 0)
            <a href="{{ route('admin.admin.payments.show', $order->payments->first()) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                <i class="fas fa-credit-card mr-2"></i>
                Lihat Pembayaran
            </a>
            @endif
        </div>
    </div>
</div>

<style>
.timeline-dot {
    position: relative;
}

.timeline-dot::before {
    content: '';
    position: absolute;
    top: 0;
    left: -1px;
    width: 2px;
    height: 100%;
    background: #e5e7eb;
}

.timeline-dot:last-child::before {
    display: none;
}
</style>
@endsection