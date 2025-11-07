<div class="card h-100 shadow product-card">
    <!-- Product Image -->
    @if($product->images->count() > 0)
    <img src="{{ $product->getPrimaryImage() ? $product->getPrimaryImage()->getImageUrl() : asset('images/placeholder.jpg') }}" 
         class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
    @else
    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
        <i class="fas fa-image fa-3x text-muted"></i>
    </div>
    @endif

    <div class="card-body d-flex flex-column">
        <!-- Product Status Badge -->
        <div class="mb-2">
            @include('products.partials.status-badge', ['status' => $product->status])
        </div>

        <!-- Product Name -->
        <h5 class="card-title">{{ $product->name }}</h5>
        
        <!-- Product Description -->
        <p class="card-text text-muted small flex-grow-1">
            {{ Str::limit($product->description, 100) }}
        </p>

        <!-- Product Details -->
        <div class="mb-2">
            <small class="text-muted">
                <i class="fas fa-tag me-1"></i>{{ $product->category->name }}
            </small>
            <br>
            <small class="text-muted">
                <i class="fas fa-chalkboard me-1"></i>{{ $product->class->getFullName() }}
            </small>
        </div>

        <!-- Price & Stock -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="h5 text-primary mb-0">Rp {{ number_format($product->price) }}</span>
            <small class="text-muted">
                <i class="fas fa-box me-1"></i>Stok: {{ $product->getStockQuantity() }}
            </small>
        </div>

        <!-- Action Buttons -->
        <div class="d-grid gap-2">
            <!-- View Button -->
            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-eye me-1"></i>Detail
            </a>

            <!-- Edit & Delete Buttons (for authorized users) -->
            @can('product.edit')
                @if($product->class->teacher_id == auth()->id() || auth()->user()->hasRole(['super_admin', 'admin']))
                <div class="btn-group w-100" role="group">
                    <a href="{{ route('products.edit', $product) }}" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    @can('product.delete')
                    <button type="button" class="btn btn-outline-danger btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#deleteModal{{ $product->product_id }}">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                    @endcan
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal{{ $product->product_id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Hapus Produk</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin menghapus produk <strong>{{ $product->name }}</strong>?</p>
                                <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <form action="{{ route('products.destroy', $product) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endcan

            <!-- Add to Cart Button (for approved products) -->
            @if($product->isApproved() && $product->getStockQuantity() > 0)
                @cannot('product.create') <!-- Students & regular teachers can add to cart -->
                <button class="btn btn-success btn-sm add-to-cart" 
                        data-product-id="{{ $product->product_id }}"
                        data-product-name="{{ $product->name }}">
                    <i class="fas fa-cart-plus me-1"></i>Tambahkan
                </button>
                @endcannot
            @elseif($product->isApproved() && $product->getStockQuantity() <= 0)
                <button class="btn btn-secondary btn-sm" disabled>
                    <i class="fas fa-times me-1"></i>Habis
                </button>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        
        // Simple add to cart (you can enhance this with quantity selection)
        $.ajax({
            url: '{{ route("cart.add") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: 1
            },
            success: function(response) {
                // Show success message
                const alert = `
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        ${productName} berhasil ditambahkan ke keranjang
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('.container-fluid').prepend(alert);
                
                // Update cart count in sidebar
                updateCartCount();
            },
            error: function(xhr) {
                const error = xhr.responseJSON?.message || 'Terjadi kesalahan';
                const alert = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        ${error}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                $('.container-fluid').prepend(alert);
            }
        });
    });
    
    function updateCartCount() {
        // You can implement cart count update logic here
        // This would typically involve an API call to get updated cart count
        console.log('Cart updated - refresh page to see updated count');
    }
});
</script>
@endpush