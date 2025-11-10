<!-- resources/views/components/user/cart-modal.blade.php -->
<div id="cart-modal" class="cart-modal fixed inset-0 z-50 hidden">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="toggleCart()"></div>
    
    <!-- Modal Content -->
    <div class="absolute bottom-0 left-0 right-0 bg-white dark:bg-secondary rounded-t-2xl max-h-[80vh] overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-700">
            <h2 class="text-lg font-bold text-primary dark:text-light">
                Keranjang Belanja 
                <span id="cart-item-count" class="text-accent">(0 items)</span>
            </h2>
            <button onclick="toggleCart()" class="text-gray-500 hover:text-gray-700 dark:text-slate-400">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="p-4 overflow-y-auto max-h-96" id="cart-items-container">
            <div class="text-center text-gray-500 dark:text-slate-400 py-8">
                <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                <p>Keranjang belanja kosong</p>
                <button onclick="toggleCart()" class="mt-4 text-accent hover:text-accent/80">
                    Mulai Belanja
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 dark:border-slate-700 p-4">
            <div class="flex justify-between items-center mb-3">
                <span class="font-semibold text-primary dark:text-light">Subtotal:</span>
                <span id="cart-subtotal" class="font-bold text-accent text-lg">Rp 0</span>
            </div>
            <button id="checkout-btn" 
                    class="w-full bg-accent text-white py-3 rounded-lg font-semibold hover:bg-accent/90 transition disabled:opacity-50 disabled:cursor-not-allowed"
                    disabled>
                Checkout Sekarang
            </button>
        </div>
    </div>
</div>

<script>
// Load cart data when modal opens
function loadCartData() {
    fetch('/api/cart')
        .then(response => response.json())
        .then(data => {
            updateCartModal(data.cart);
        })
        .catch(error => {
            console.error('Error loading cart:', error);
        });
}

function updateCartModal(cartData) {
    const container = document.getElementById('cart-items-container');
    const itemCount = document.getElementById('cart-item-count');
    const subtotal = document.getElementById('cart-subtotal');
    const checkoutBtn = document.getElementById('checkout-btn');

    if (cartData.items.length === 0) {
        container.innerHTML = `
            <div class="text-center text-gray-500 dark:text-slate-400 py-8">
                <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                <p>Keranjang belanja kosong</p>
                <button onclick="toggleCart()" class="mt-4 text-accent hover:text-accent/80">
                    Mulai Belanja
                </button>
            </div>
        `;
        checkoutBtn.disabled = true;
    } else {
        let itemsHTML = '';
        cartData.items.forEach(item => {
            itemsHTML += `
                <div class="flex items-center space-x-3 py-3 border-b border-gray-200 dark:border-slate-700">
                    <img src="${item.product.images[0]?.file_path ? '/storage/' + item.product.images[0].file_path : ''}" 
                         alt="${item.product.name}"
                         class="w-12 h-12 rounded object-cover">
                    <div class="flex-1">
                        <h4 class="font-medium text-primary dark:text-light">${item.product.name}</h4>
                        ${item.variant ? `<p class="text-sm text-gray-600 dark:text-slate-400">${item.variant.name}</p>` : ''}
                        <p class="text-sm text-gray-600 dark:text-slate-400">
                            Rp ${formatPrice(item.price_snapshot)} x ${item.qty}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-primary dark:text-light">
                            Rp ${formatPrice(item.price_snapshot * item.qty)}
                        </p>
                        <button onclick="removeFromCart(${item.cart_item_id})" 
                                class="text-red-500 hover:text-red-700 text-sm mt-1">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        container.innerHTML = itemsHTML;
        checkoutBtn.disabled = false;
    }

    if (itemCount) {
        itemCount.textContent = `(${cartData.item_count} items)`;
    }
    if (subtotal) {
        subtotal.textContent = `Rp ${formatPrice(cartData.subtotal)}`;
    }
}

function removeFromCart(cartItemId) {
    fetch(`/api/cart/items/${cartItemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        updateCartModal(data.cart);
        updateCartBadge(data.cart.item_count);
    })
    .catch(error => {
        console.error('Error removing item:', error);
    });
}

function updateCartBadge(count) {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        cartCount.textContent = count;
    }
}

// Update modal when opened
document.getElementById('cart-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        loadCartData();
    }
});
</script>