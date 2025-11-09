@extends('layouts.app')

@section('title', 'Pembayaran - Order #' . $order->invoice_number)
@section('page-title', 'Pembayaran Order')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-credit-card me-2"></i>Pembayaran Order #{{ $order->invoice_number }}
                </h5>
            </div>
            <div class="card-body">
                <!-- Order Summary -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Detail Order:</h6>
                        <p class="mb-1"><strong>Total:</strong> Rp {{ number_format($order->total_amount) }}</p>
                        <p class="mb-1"><strong>Items:</strong> {{ $order->items->count() }} produk</p>
                        <p class="mb-0"><strong>Status:</strong> 
                            <span class="badge bg-warning">Menunggu Pembayaran</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6>Instruksi:</h6>
                        <ul class="small text-muted">
                            <li>Klik tombol "Bayar Sekarang" untuk membuka halaman pembayaran</li>
                            <li>Pilih metode pembayaran QRIS</li>
                            <li>Scan QR code yang muncul menggunakan aplikasi e-wallet atau mobile banking</li>
                            <li>Pembayaran akan diproses otomatis</li>
                        </ul>
                    </div>
                </div>

                <!-- Payment Button -->
                <div class="text-center">
                    <button id="pay-button" class="btn btn-success btn-lg">
                        <i class="fas fa-qrcode me-2"></i>Bayar Sekarang - Rp {{ number_format($order->total_amount) }}
                    </button>
                    
                    <div class="mt-3">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Kembali ke Order
                        </a>
                    </div>
                </div>

                <!-- Payment Status -->
                <div id="payment-status" class="mt-4" style="display: none;">
                    <div class="alert alert-info">
                        <i class="fas fa-sync-alt fa-spin me-2"></i>
                        Memproses pembayaran...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $client_key }}"></script>
<script>
document.getElementById('pay-button').onclick = function(){
    // Show loading
    document.getElementById('payment-status').style.display = 'block';
    
    snap.pay('{{ $snap_token }}', {
        onSuccess: function(result){
            window.location.href = '{{ route('payments.success', $order) }}';
        },
        onPending: function(result){
            // Redirect to order detail page, payment still pending
            window.location.href = '{{ route('orders.show', $order) }}';
        },
        onError: function(result){
            window.location.href = '{{ route('payments.failure', $order) }}';
        },
        onClose: function(){
            // User closed the popup without finishing the payment
            document.getElementById('payment-status').style.display = 'none';
        }
    });
};
</script>
@endpush