@php
    $items = isset($order) ? $order->items : $cart->items;
@endphp

@foreach($items as $item)
<div class="order-item border-bottom pb-3 mb-3">
    <div class="row align-items-center">
        <!-- Product Image -->
        <div class="col-2">
            @if($item->product->images->count() > 0)
            <img src="{{ $item->product->getPrimaryImage()->getImageUrl() }}" 
                 alt="{{ $item->product->name }}" 
                 class="img-fluid rounded" 
                 style="width: 60px; height: 60px; object-fit: cover;">
            @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                 style="width: 60px; height: 60px;">
                <i class="fas fa-image text-muted"></i>
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="col-6">
            <h6 class="mb-1">{{ $item->getProductName() }}</h6>
            <p class="text-muted small mb-1">
                {{ $item->product->class->getFullName() }}
            </p>
            @if($item->extras->count() > 0)
            <div class="small text-muted">
                <strong>Extras:</strong>
                @foreach($item->extras as $extra)
                <span class="badge bg-secondary">{{ $extra->extra->name }} ({{ $extra->qty }})</span>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Quantity & Price -->
        <div class="col-4 text-end">
            <div class="mb-1">{{ $item->qty }} x Rp {{ number_format(isset($item->price) ? $item->price : $item->price_snapshot) }}</div>
            <strong class="text-primary">Rp {{ number_format(isset($item->subtotal) ? $item->subtotal : $item->getSubtotal()) }}</strong>
        </div>
    </div>
</div>
@endforeach

<!-- Total -->
<div class="row mt-3">
    <div class="col-12 text-end">
        <h5 class="text-primary">
            Total: Rp {{ number_format(isset($order) ? $order->total_amount : $cart->getSubtotal()) }}
        </h5>
    </div>
</div>