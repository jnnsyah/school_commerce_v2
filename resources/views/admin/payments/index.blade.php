<!-- resources/views/admin/payments/index.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Kelola Pembayaran - School Commerce')
@section('page-title', 'Kelola Pembayaran')
@section('page-subtitle', 'Monitor dan kelola semua transaksi pembayaran')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-primary dark:text-light">Semua Pembayaran</h2>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                Total {{ $payments->total() }} transaksi ditemukan
            </p>
        </div>
        
        <div class="flex space-x-3 mt-4 sm:mt-0">
            <a href="/admin/payments/manual" 
               class="inline-flex items-center px-4 py-2 border border-yellow-300 text-yellow-700 rounded-lg 
                      hover:bg-yellow-50 dark:border-yellow-600 dark:text-yellow-300 dark:hover:bg-yellow-900/20 transition">
                <i class="fas fa-hand-holding-usd mr-2"></i>
                Konfirmasi Manual
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-4">
        <form action="{{ url()->current() }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                           focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                           bg-white dark:bg-secondary text-primary dark:text-light">
                    <option value="">Semua Status</option>
                    @foreach($paymentStatuses as $status)
                        <option value="{{ $status->status_id }}" {{ request('status') == $status->status_id ? 'selected' : '' }}>
                            {{ $status->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Method Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Metode</label>
                <select name="method" class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                                           focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                                           bg-white dark:bg-secondary text-primary dark:text-light">
                    <option value="">Semua Metode</option>
                    @foreach($paymentMethods as $method)
                        <option value="{{ $method->method_id }}" {{ request('method') == $method->method_id ? 'selected' : '' }}>
                            {{ $method->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-slate-300 mb-2">Dari Tanggal</label>
                <input type="date" 
                       name="date_from" 
                       value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-slate-600 rounded-lg 
                              focus:outline-none focus:ring-2 focus:ring-accent focus:border-accent
                              bg-white dark:bg-secondary text-primary dark:text-light">
            </div>

            <!-- Date To -->
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

    <!-- Payments Table -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-slate-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Order & Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Metode
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-slate-400 uppercase tracking-wider">
                            Amount
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
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                        <!-- Order & Customer -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-accent/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-receipt text-accent"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-primary dark:text-light">
                                        #{{ $payment->order->invoice_number }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-slate-400">
                                        {{ $payment->order->user->name }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Method -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <i class="fas 
                                    @if($payment->method_id == 1) fa-money-bill-wave text-green-500
                                    @elseif($payment->method_id == 2) fa-qrcode text-blue-500
                                    @else fa-credit-card text-purple-500 @endif 
                                    mr-2"></i>
                                <span class="text-sm text-primary dark:text-light">
                                    {{ $payment->method->name }}
                                </span>
                            </div>
                        </td>

                        <!-- Amount -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-accent">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($payment->status_id == 1) bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300
                                @elseif($payment->status_id == 2) bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                @else bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 @endif">
                                <i class="fas 
                                    @if($payment->status_id == 1) fa-clock
                                    @elseif($payment->status_id == 2) fa-check-circle
                                    @else fa-times-circle @endif 
                                    mr-1"></i>
                                {{ $payment->status->name }}
                            </span>
                            @if($payment->paid_at)
                            <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">
                                {{ $payment->paid_at->format('d M Y H:i') }}
                            </div>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                            {{ $payment->created_at->format('d M Y') }}
                            <div class="text-xs">
                                {{ $payment->created_at->format('H:i') }}
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="/admin/payments/{{ $payment->payment_id }}/details" 
                                   class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($payment->method_id == 1 && $payment->status_id == 1) <!-- Cash & Pending -->
                                <form action="/admin/payments/{{ $payment->payment_id }}/confirm" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Konfirmasi pembayaran tunai ini?')"
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 transition">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                
                                <form action="/admin/payments/{{ $payment->payment_id }}/cancel" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Batalkan pembayaran ini?')"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-credit-card text-4xl text-gray-400 dark:text-slate-600 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-light mb-2">Tidak ada pembayaran</h3>
                            <p class="text-gray-500 dark:text-slate-400">Belum ada transaksi pembayaran</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700">
            {{ $payments->links('components.shared.pagination') }}
        </div>
        @endif
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-money-bill-wave text-blue-600 dark:text-blue-400 text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Total Pendapatan</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">
                        Rp {{ number_format($payments->where('status_id', 2)->sum('amount'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Berhasil</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">
                        {{ $payments->where('status_id', 2)->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-yellow-600 dark:text-yellow-400 text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Pending</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">
                        {{ $payments->where('status_id', 1)->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-times-circle text-red-600 dark:text-red-400 text-lg"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600 dark:text-slate-400">Gagal</p>
                    <p class="text-2xl font-bold text-primary dark:text-light">
                        {{ $payments->where('status_id', 3)->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection