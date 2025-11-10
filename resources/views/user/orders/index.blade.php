<!-- resources/views/user/orders/index.blade.php -->
@extends('layouts.user-app')

@section('title', 'Order History - School Commerce')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary dark:text-light mb-2">Riwayat Pesanan</h1>
        <p class="text-gray-600 dark:text-slate-400">Lihat status dan riwayat pesanan Anda</p>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
                <!-- Order Header -->
                <div class="p-4 border-b border-gray-200 dark:border-slate-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="font-semibold text-primary dark:text-light">
                                Order #{{ $order->invoice_number }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-slate-400">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="flex items-center space-x-4 mt-2 sm:mt-0">
                            @include('components.user.order-tracking', ['order' => $order])
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                @if($order->status_id == 1) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                                @elseif($order->status_id == 2) bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                @elseif($order->status_id == 3) bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300
                                @elseif($order->status_id == 4) bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                @else bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 @endif">
                                {{ $order->status->name }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="p-4">
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                        <div class="flex items-center space-x-3">
                            @if($item->product->images->count() > 0)
                                <img src="{{ asset('storage/' . $item->product->images->first()->file_path) }}" 
                                     alt="{{ $item->product->name }}"
                                     class="w-12 h-12 rounded object-cover lazy-load"
                                     loading="lazy">
                            @else
                                <div class="w-12 h-12 rounded bg-gray-100 dark:bg-slate-800 flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-medium text-primary dark:text-light">
                                    {{ $item->product->name }}
                                </h4>
                                @if($item->variant)
                                    <p class="text-sm text-gray-600 dark:text-slate-400">
                                        Variant: {{ $item->variant->name }}
                                    </p>
                                @endif
                                <p class="text-sm text-gray-600 dark:text-slate-400">
                                    {{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-primary dark:text-light">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Order Total -->
                    <div class="flex justify-between items-center pt-4 mt-4 border-t border-gray-200 dark:border-slate-700">
                        <span class="font-semibold text-primary dark:text-light">Total</span>
                        <span class="text-lg font-bold text-accent">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <!-- Order Actions -->
                <div class="px-4 py-3 bg-gray-50 dark:bg-slate-800 border-t border-gray-200 dark:border-slate-700">
                    <div class="flex justify-end space-x-3">
                        <a href="/user/orders/{{ $order->order_id }}"
                           class="px-4 py-2 text-sm font-medium text-primary dark:text-light border border-gray-300 dark:border-slate-600 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                            Detail
                        </a>
                        @if($order->status_id == 1) <!-- Pending -->
                            <form action="/user/orders/{{ $order->order_id }}/cancel" method="POST">
                                @csrf
                                @method('POST')
                                <button type="submit" 
                                        class="px-4 py-2 text-sm font-medium text-red-600 border border-red-300 rounded-lg hover:bg-red-50 transition">
                                    Batalkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <i class="fas fa-clipboard-list text-4xl text-gray-400 dark:text-slate-600 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 dark:text-light mb-2">Belum ada pesanan</h3>
                <p class="text-gray-500 dark:text-slate-400 mb-4">Mulai berbelanja untuk melihat riwayat pesanan di sini</p>
                <a href="/user/products"
                   class="inline-flex items-center px-4 py-2 bg-accent text-white rounded-lg hover:bg-accent/90 transition">
                    <i class="fas fa-store mr-2"></i>
                    Belanja Sekarang
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="mt-6">
        {{ $orders->links('components.shared.pagination') }}
    </div>
    @endif
</div>
@endsection