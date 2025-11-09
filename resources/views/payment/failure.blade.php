@extends('layouts.app')

@section('title', 'Pembayaran Gagal')
@section('page-title', 'Pembayaran Gagal')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <div class="text-danger mb-4">
                    <i class="fas fa-times-circle fa-5x"></i>
                </div>
                <h3 class="text-danger mb-3">Pembayaran Gagal</h3>
                <p class="text-muted mb-4">
                    Maaf, pembayaran untuk order #{{ $order->invoice_number }} tidak berhasil.
                    Silakan coba lagi atau hubungi admin.
                </p>

                <div class="d-grid gap-2 d-md-block">
                    <a href="{{ route('payments.checkout', $order) }}" class="btn btn-primary">
                        <i class="fas fa-redo me-2"></i>Coba Bayar Lagi
                    </a>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection