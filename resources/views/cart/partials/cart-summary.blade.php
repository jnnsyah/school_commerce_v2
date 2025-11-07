<div class="card shadow">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-receipt me-2"></i>Ringkasan Belanja
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
        <div class="d-flex justify-content-between mb-2">
            <span>Pengiriman:</span>
            <span>Gratis</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-3">
            <strong>Total:</strong>
            <strong class="h5 text-primary">Rp {{ number_format($cart->getSubtotal()) }}</strong>
        </div>
        
        <!-- Stock Warning -->
        @php
            $outOfStockItems = $cart->items->filter(function($item) {
                if ($item->variant_id) {
                    return $item->variant->getCurrentStock() < $item->qty;
                }
                return $item->product->getStockQuantity() < $item->qty;
            });
        @endphp
        
        @if($outOfStockItems->count() > 0)
        <div class="alert alert-warning small">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Beberapa item stok tidak mencukupi. Silakan periksa kembali quantity.
        </div>
        @endif
    </div>
</div>