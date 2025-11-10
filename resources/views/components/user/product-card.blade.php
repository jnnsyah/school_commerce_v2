<!-- resources/views/components/user/product-card.blade.php -->
@props(['product'])

<div class="bg-white dark:bg-secondary rounded-lg shadow-sm border border-gray-200 dark:border-slate-700 
            overflow-hidden hover:shadow-md transition-shadow duration-300">
    <!-- Product Image -->
    <div class="relative aspect-square bg-gray-100 dark:bg-slate-800">
        @if($product->images->count() > 0)
            <img 
                src="{{ asset('storage/' . $product->images->first()->file_path) }}" 
                alt="{{ $product->name }}"
                class="w-full h-full object-cover lazy-load"
                loading="lazy"
            >
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-400 dark:text-slate-600">
                <i class="fas fa-image text-3xl"></i>
            </div>
        @endif
        
        <!-- Seller Badge -->
        <div class="absolute top-2 left-2">
            <span class="bg-primary/90 text-white text-xs px-2 py-1 rounded-full">
                {{ $product->class->grade->name }} {{ $product->class->major->short_name }}
            </span>
        </div>
    </div>

    <!-- Product Info -->
    <div class="p-4">
        <h3 class="font-semibold text-primary dark:text-light mb-1 line-clamp-2">
            {{ $product->name }}
        </h3>
        
        <p class="text-sm text-gray-600 dark:text-slate-400 mb-2 line-clamp-2">
            {{ Str::limit($product->description, 60) }}
        </p>

        <div class="flex items-center justify-between">
            <span class="text-lg font-bold text-accent">
                Rp {{ number_format($product->price, 0, ',', '.') }}
            </span>
            
            <!-- Add to Cart Button -->
            <button onclick="addToCart({{ $product->product_id }})"
                    class="bg-accent text-white p-2 rounded-lg hover:bg-accent/90 transition 
                           transform hover:scale-105 active:scale-95">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    // Show variant selection modal if product has variants
    // For now, just add directly
    fetch('/api/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        // Update cart UI
        updateCartUI(data.cart);
        // Show success message
        showAlert('Produk berhasil ditambahkan ke keranjang', 'success');
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Gagal menambahkan produk', 'error');
    });
}

function updateCartUI(cartData) {
    // Update cart count
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        cartCount.textContent = cartData.item_count;
    }
    
    // Update cart total
    const cartTotal = document.getElementById('cart-total');
    if (cartTotal) {
        cartTotal.textContent = formatPrice(cartData.subtotal);
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(price);
}

function showAlert(message, type) {
    // Simple alert implementation
    alert(message);
}
</script>