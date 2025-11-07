@extends('layouts.app')

@section('title', 'Keranjang Belanja')
@section('page-title', 'Keranjang Belanja')

@section('page-actions')
    @if(!$cart->isEmpty())
    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Kosongkan keranjang?')">
            <i class="fas fa-trash me-1"></i>Kosongkan
        </button>
    </form>
    @endif
    <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
        <i class="fas fa-shopping-bag me-1"></i>Lanjut Belanja
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        @if($cart->isEmpty())
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Keranjang belanja kosong</h5>
                <p class="text-muted">Silakan tambahkan produk ke keranjang Anda</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-1"></i>Mulai Belanja
                </a>
            </div>
        </div>
        @else
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Item dalam Keranjang ({{ $cart->getTotalItems() }})
                </h5>
            </div>
            <div class="card-body">
                @include('cart.partials.cart-items')
            </div>
        </div>
        @endif
    </div>

    @if(!$cart->isEmpty())
    <div class="col-lg-4">
        @include('cart.partials.cart-summary')
        
        <!-- Checkout Button -->
        <div class="d-grid mt-3">
            <a href="{{ route('orders.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-credit-card me-2"></i>Checkout Sekarang
            </a>
        </div>
        
        <!-- Additional Info -->
        <div class="card shadow mt-4">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-info-circle me-2 text-primary"></i>Informasi
                </h6>
                <ul class="list-unstyled small text-muted">
                    <li><i class="fas fa-check text-success me-2"></i>Stok terjamin</li>
                    <li><i class="fas fa-check text-success me-2"></i>Harga sudah final</li>
                    <li><i class="fas fa-check text-success me-2"></i>Pembayaran mudah</li>
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection