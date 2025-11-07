@foreach($cart->items as $item)
<div class="cart-item border-bottom pb-3 mb-3">
    <div class="row align-items-center">
        <!-- Product Image -->
        <div class="col-2">
            @if($item->product->images->count() > 0)
            <img src="{{ $item->product->getPrimaryImage()->getImageUrl() }}" 
                 alt="{{ $item->product->name }}" 
                 class="img-fluid rounded" 
                 style="width: 80px; height: 80px; object-fit: cover;">
            @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                 style="width: 80px; height: 80px;">
                <i class="fas fa-image text-muted"></i>
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="col-5">
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

        <!-- Quantity -->
        <div class="col-2">
            <form action="{{ route('cart.update', $item) }}" method="POST" class="quantity-form">
                @csrf
                <div class="input-group input-group-sm">
                    <input type="number" name="quantity" value="{{ $item->qty }}" 
                           min="1" max="100" class="form-control quantity-input">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Price -->
        <div class="col-2 text-end">
            <div class="h6 mb-0">Rp {{ number_format($item->getSubtotal()) }}</div>
            <small class="text-muted">Rp {{ number_format($item->price_snapshot) }} / item</small>
        </div>

        <!-- Remove -->
        <div class="col-1 text-end">
            <form action="{{ route('cart.remove', $item) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger" 
                        onclick="return confirm('Hapus item dari keranjang?')">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script>
$(document).ready(function() {
    $('.quantity-form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const quantity = form.find('.quantity-input').val();
        
        if (quantity < 1 || quantity > 100) {
            alert('Quantity harus antara 1-100');
            return;
        }
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                quantity: quantity
            },
            success: function() {
                location.reload();
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });
});
</script>
@endpush