@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')
@section('page-title', 'Pembayaran Berhasil')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="text-success mb-4">
                    <i class="fas fa-check-circle fa-5x"></i>
                </div>
                <h3 class="text-success mb-3">Pembayaran Berhasil!</h3>
                <p class="text-muted mb-4">
                    Terima kasih telah melakukan pembayaran. Order Anda sedang diproses.
                </p>
                
                <div class="mb-4">
                    <p><strong>Order ID:</strong> {{ $order->invoice_number }}</p>
                    <p><strong>Total Pembayaran:</strong> Rp {{ number_format($order->total_amount) }}</p>
                </div>

                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">
                        <i class="fas fa-receipt me-2"></i>Lihat Detail Order
                    </a>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>Daftar Order Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection