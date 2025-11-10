<!-- resources/views/admin/payments/show.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Detail Pembayaran - School Commerce')
@section('page-title', 'Detail Pembayaran')
@section('page-subtitle', 'Informasi lengkap transaksi pembayaran')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-primary dark:text-light">Detail Pembayaran</h2>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                #{{ $payment->payment_id }} - {{ $payment->order->invoice_number }}
            </p>
        </div>
        
        <a href="/admin/payments" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 
                  rounded-lg text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Card -->
            <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Informasi Pembayaran</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Status Pembayaran</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                            @if($payment->status_id == 1) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                            @elseif($payment->status_id == 2) bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                            @else bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 @endif">
                            <i class="fas 
                                @if($payment->status_id == 1) fa-clock
                                @elseif($payment->status_id == 2) fa-check-circle
                                @else fa-times-circle @endif 
                                mr-2"></i>
                            {{ $payment->status->name }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Metode Pembayaran</label>
                        <p class="text-sm text-primary dark:text-light mt-1 flex items-center">
                            <i class="fas 
                                @if($payment->method_id == 1) fa-money-bill-wave text-green-500
                                @elseif($payment->method_id == 2) fa-qrcode text-blue-500
                                @else fa-credit-card text-purple-500 @endif 
                                mr-2"></i>
                            {{ $payment->method->name }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Amount</label>
                        <p class="text-2xl font-bold text-accent mt-1">
                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Dibuat Pada</label>
                        <p class="text-sm text-primary dark:text-light mt-1">
                            {{ $payment->created_at->format('d M Y H:i:s') }}
                        </p>
                    </div>

                    @if($payment->paid_at)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Dibayar Pada</label>
                        <p class="text-sm text-primary dark:text-light mt-1">
                            {{ $payment->paid_at->format('d M Y H:i:s') }}
                        </p>
                    </div>
                    @endif

                    @if($payment->approved_by)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Dikonfirmasi Oleh</label>
                        <p class="text-sm text-primary dark:text-light mt-1">
                            {{ $payment->approvedBy->name ?? 'System' }}
                        </p>
                    </div>
                    @endif

                    @if($payment->reference_id)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-slate-300">Reference ID</label>
                        <p class="text-sm text-primary dark:text-light mt-1 font-mono">
                            {{ $payment->reference_id }}
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                @if($payment->method_id == 1 && $payment->status_id == 1)
                <div class="flex space-x-3 mt-6 pt-6 border-t border-gray-200 dark:border-slate-700">
                    <form action="/admin/payments/{{ $payment->payment_id }}/confirm" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Konfirmasi pembayaran tunai ini?')"
                                class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition font-medium">
                            <i class="fas fa-check mr-2"></i>
                            Konfirmasi Pembayaran
                        </button>
                    </form>
                    
                    <form action="/admin/payments/{{ $payment->payment_id }}/cancel" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Batalkan pembayaran ini?')"
                                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Tolak Pembayaran
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Order Information -->
            <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Informasi Order</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-slate-400">Invoice Number:</span>
                        <span class="font-medium text-primary dark:text-light">#{{ $payment->order->invoice_number }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-slate-400">Status Order:</span>
                        <span class="font-medium text-primary dark:text-light">{{ $payment->order->status->name }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-slate-400">Total Amount:</span>
                        <span class="font-medium text-accent">Rp {{ number_format($payment->order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-slate-400">Dibuat Pada:</span>
                        <span class="text-primary dark:text-light">{{ $payment->order->created_at->format('d M Y H:i') }}</span>
                    </div>

                    <div class="pt-3 border-t border-gray-200 dark:border-slate-700">
                        <a href="/admin/orders/{{ $payment->order->order_id }}" 
                           class="inline-flex items-center text-accent hover:text-accent/80 transition">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Lihat Detail Order Lengkap
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Informasi Customer</h3>
                
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-accent/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-accent"></i>
                    </div>
                    <div>
                        <p class="font-medium text-primary dark:text-light">{{ $payment->order->user->name }}</p>
                        <p class="text-sm text-gray-600 dark:text-slate-400">{{ $payment->order->user->email }}</p>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-slate-400">Telepon:</span>
                        <span class="text-primary dark:text-light">{{ $payment->order->user->no_hp ?? '-' }}</span>
                    </div>
                    
                    @if($payment->order->user->student)
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-slate-400">Kelas:</span>
                        <span class="text-primary dark:text-light">
                            {{ $payment->order->user->student->class->grade->name }} 
                            {{ $payment->order->user->student->class->major->short_name }}
                            {{ $payment->order->user->student->class->section->name }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-slate-400">NISN:</span>
                        <span class="text-primary dark:text-light">{{ $payment->order->user->student->nisn }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Transaction Logs -->
            <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
                <h3 class="text-lg font-semibold text-primary dark:text-light mb-4">Riwayat Transaksi</h3>
                
                <div class="space-y-3">
                    @forelse($payment->order->transactionLogs->sortByDesc('created_at') as $log)
                    <div class="flex items-start space-x-3 p-3 rounded-lg bg-gray-50 dark:bg-slate-800">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center">
                            <i class="fas fa-history text-accent text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-primary dark:text-light">{{ $log->note }}</p>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
                                {{ $log->created_at->format('d M Y H:i') }}
                                @if($log->user)
                                • oleh {{ $log->user->name }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500 dark:text-slate-400 text-center py-4">
                        Tidak ada riwayat transaksi
                    </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection