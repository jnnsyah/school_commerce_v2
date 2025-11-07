@extends('layouts.app')

@section('title', 'Checkout Pesanan')
@section('page-title', 'Checkout Pesanan')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>Ringkasan Pesanan
                </h5>
            </div>
            <div class="card-body">
                @include('orders.partials.order-items')
            </div>
        </div>

        <!-- Customer Notes -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-edit me-2"></i>Catatan Pesanan (Opsional)
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="customer_notes" class="form-label">Catatan untuk Penjual</label>
                        <textarea class="form-control" id="customer_notes" name="customer_notes" 
                                  rows="3" placeholder="Contoh: Warna tertentu, ukuran, dll.">{{ old('customer_notes') }}</textarea>
                    </div>

                    <!-- Terms Agreement -->
                    <div class="form-check mb-3">
                        <input class="form-check-input @error('agree_terms') is-invalid @enderror" 
                               type="checkbox" id="agree_terms" name="agree_terms" value="1" required>
                        <label class="form-check-label" for="agree_terms">
                            Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">syarat dan ketentuan</a> yang berlaku
                        </label>
                        @error('agree_terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Checkout Button -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-credit-card me-2"></i>Buat Pesanan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-receipt me-2"></i>Total Pesanan
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>Rp {{ number_format($cart->getSubtotal()) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Biaya Layanan:</span>
                    <span>Rp 0</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total:</strong>
                    <strong class="h4 text-primary">Rp {{ number_format($cart->getSubtotal()) }}</strong>
                </div>
                
                <!-- Payment Info -->
                <div class="alert alert-info small">
                    <i class="fas fa-info-circle me-2"></i>
                    Pembayaran dapat dilakukan via QRIS atau tunai di sekolah.
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="card shadow mt-4">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-user me-2 text-primary"></i>Informasi Pembeli
                </h6>
                <p class="mb-1"><strong>{{ auth()->user()->name }}</strong></p>
                <p class="mb-1 small text-muted">{{ auth()->user()->email }}</p>
                <p class="mb-0 small text-muted">{{ auth()->user()->no_hp }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Syarat dan Ketentuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Ketentuan Pembelian:</h6>
                <ul>
                    <li>Pesanan dapat dibatalkan sebelum status "Diproses"</li>
                    <li>Stok produk dapat berubah sewaktu-waktu</li>
                    <li>Harga sudah termasuk semua biaya</li>
                    <li>Pembayaran dilakukan via QRIS atau tunai</li>
                    <li>Pengambilan barang di sekolah</li>
                </ul>
                
                <h6>Kebijakan Pengembalian:</h6>
                <ul>
                    <li>Produk yang sudah dibeli tidak dapat dikembalikan</li>
                    <li>Klaim hanya untuk produk yang tidak sesuai pesanan</li>
                    <li>Hubungi admin untuk masalah produk</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#checkoutForm').submit(function() {
        // Add loading state
        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');
        return true;
    });
});
</script>
@endpush