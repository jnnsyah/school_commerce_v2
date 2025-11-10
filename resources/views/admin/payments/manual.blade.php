<!-- resources/views/admin/payments/manual.blade.php -->
@extends('layouts.admin-app')

@section('title', 'Konfirmasi Pembayaran Manual - School Commerce')
@section('page-title', 'Konfirmasi Pembayaran Manual')
@section('page-subtitle', 'Konfirmasi pembayaran tunai dari siswa')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-primary dark:text-light">Pembayaran Tunai Menunggu Konfirmasi</h2>
            <p class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                {{ $payments->total() }} pembayaran tunai perlu dikonfirmasi
            </p>
        </div>
        
        <a href="/admin/payments" 
           class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-slate-600 
                  rounded-lg text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-800 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Semua Pembayaran
        </a>
    </div>

    <!-- Manual Payments List -->
    <div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-yellow-50 dark:bg-yellow-900/20">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 dark:text-yellow-300 uppercase tracking-wider">
                            Order & Customer
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 dark:text-yellow-300 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 dark:text-yellow-300 uppercase tracking-wider">
                            Tanggal Order
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 dark:text-yellow-300 uppercase tracking-wider">
                            Status Order
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-yellow-800 dark:text-yellow-300 uppercase tracking-wider">
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
                                <div class="flex-shrink-0 h-10 w-10 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-yellow-600 dark:text-yellow-400"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-primary dark:text-light">
                                        #{{ $payment->order->invoice_number }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-slate-400">
                                        {{ $payment->order->user->name }}
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-slate-500">
                                        {{ $payment->order->user->student->class->grade->name }} 
                                        {{ $payment->order->user->student->class->major->short_name }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Amount -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-lg font-bold text-accent">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </div>
                        </td>

                        <!-- Order Date -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-slate-400">
                            {{ $payment->order->created_at->format('d M Y') }}
                            <div class="text-xs">
                                {{ $payment->order->created_at->format('H:i') }}
                            </div>
                        </td>

                        <!-- Order Status -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                                <i class="fas fa-clock mr-1"></i>
                                {{ $payment->order->status->name }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="/admin/orders/{{ $payment->order->order_id }}" 
                                   class="inline-flex items-center px-3 py-1 border border-gray-300 dark:border-slate-600 
                                          rounded text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-800 transition text-sm">
                                    <i class="fas fa-eye mr-1"></i>
                                    Detail
                                </a>
                                
                                <form action="/admin/payments/{{ $payment->payment_id }}/confirm" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Konfirmasi pembayaran tunai dari {{ $payment->order->user->name }} sebesar Rp {{ number_format($payment->amount, 0, ',', '.') }}?')"
                                            class="inline-flex items-center px-3 py-1 bg-green-600 text-white rounded 
                                                   hover:bg-green-700 transition text-sm">
                                        <i class="fas fa-check mr-1"></i>
                                        Konfirmasi
                                    </button>
                                </form>
                                
                                <form action="/admin/payments/{{ $payment->payment_id }}/cancel" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Batalkan pembayaran dari {{ $payment->order->user->name }}?')"
                                            class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded 
                                                   hover:bg-red-700 transition text-sm">
                                        <i class="fas fa-times mr-1"></i>
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <i class="fas fa-check-circle text-4xl text-green-400 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-light mb-2">Tidak ada pembayaran menunggu</h3>
                            <p class="text-gray-500 dark:text-slate-400">Semua pembayaran tunai sudah dikonfirmasi</p>
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

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-2xl text-yellow-600 dark:text-yellow-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Menunggu Konfirmasi</p>
                    <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-200">
                        {{ $payments->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-money-bill-wave text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-800 dark:text-blue-300">Total Menunggu</p>
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-200">
                        Rp {{ number_format($payments->sum('amount'), 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users text-2xl text-green-600 dark:text-green-400"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-800 dark:text-green-300">Siswa Terlibat</p>
                    <p class="text-2xl font-bold text-green-900 dark:text-green-200">
                        {{ $payments->unique('order.user_id')->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto refresh every 30 seconds to check for new payments
setInterval(() => {
    window.location.reload();
}, 30000);
</script>
@endsection