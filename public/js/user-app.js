// public/js/user-app.js

// Global State
let currentProduct = null;
let selectedVariant = null;
let currentQuantity = 1;
let maxQuantity = 1;
let cartData = { items: [], item_count: 0, subtotal: 0 };

// Add to Cart Modal Functions
function showAddToCartModal(productId) {
    fetch(`/ajax/products/${productId}/variants`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            currentProduct = data.product;
            showProductModal(data.product, data.variants);
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Gagal memuat data produk', 'error');
        });
}

function showProductModal(product, variants) {
    // Update modal content
    const productImage = product.primary_image ? 
        `/storage/${product.primary_image.file_path}` : 
        '/images/placeholder.jpg';
        
    document.getElementById('modal-product-info').innerHTML = `
        <div class="flex space-x-3">
            <img src="${productImage}" 
                 alt="${product.name}"
                 class="w-16 h-16 rounded-lg object-cover bg-gray-100">
            <div class="flex-1">
                <h4 class="font-semibold text-primary dark:text-light text-sm leading-tight">${product.name}</h4>
                <p class="text-accent font-bold text-lg mt-1">Rp ${formatPrice(product.price)}</p>
            </div>
        </div>
    `;

    // Handle variants
    const variantSection = document.getElementById('variant-section');
    const hasVariants = variants && variants.length > 0;
    
    if (hasVariants) {
        variantSection.classList.remove('hidden');
        renderVariantOptions(variants);
        // Set first available variant as default
        const firstAvailable = variants.find(v => v.stock_at > 0);
        if (firstAvailable) {
            selectedVariant = firstAvailable.id;
            maxQuantity = firstAvailable.stock_at;
            updateVariantSelection(firstAvailable.id);
        }
    } else {
        variantSection.classList.add('hidden');
        selectedVariant = null;
        maxQuantity = product.getStockQuantity || 99;
    }

    // Reset quantity
    currentQuantity = 1;
    updateQuantityDisplay();
    updateMaxStockInfo();

    // Show modal
    document.getElementById('add-to-cart-modal').classList.remove('hidden');
}

function renderVariantOptions(variants) {
    const container = document.getElementById('variant-options');
    const basePrice = currentProduct?.price || 0;
    
    container.innerHTML = variants.map(variant => {
        const priceDifference = variant.price - basePrice;
        const priceDisplay = priceDifference > 0 ? `+ Rp ${formatPrice(priceDifference)}` : '';
        const isAvailable = variant.stock_at > 0;
        
        return `
        <div class="variant-option ${!isAvailable ? 'opacity-50' : ''}">
            <input type="radio" 
                   id="variant-${variant.id}" 
                   name="variant" 
                   value="${variant.id}" 
                   ${!isAvailable ? 'disabled' : ''}
                   onchange="selectVariant(${variant.id}, ${variant.stock_at})"
                   class="hidden">
            <label for="variant-${variant.id}" 
                   class="flex items-center justify-between p-3 border-2 border-gray-200 dark:border-slate-600 
                          rounded-lg cursor-pointer transition-all variant-label
                          ${!isAvailable ? 'cursor-not-allowed' : 'hover:border-accent'}">
                <div>
                    <span class="font-medium text-primary dark:text-light">${variant.name}</span>
                    ${priceDisplay ? `<span class="text-accent font-bold ml-2">${priceDisplay}</span>` : ''}
                </div>
                <div class="text-sm ${!isAvailable ? 'text-red-500' : 'text-gray-500 dark:text-slate-400'}">
                    ${!isAvailable ? 'Stok Habis' : `Stok: ${variant.stock_at}`}
                </div>
            </label>
        </div>
        `;
    }).join('');

    // Highlight first available variant
    const firstAvailable = variants.find(v => v.stock_at > 0);
    if (firstAvailable) {
        updateVariantSelection(firstAvailable.id);
    }
}

function selectVariant(variantId, stock) {
    selectedVariant = variantId;
    maxQuantity = stock;
    updateVariantSelection(variantId);
    
    // Reset quantity if current quantity exceeds new max
    if (currentQuantity > maxQuantity) {
        currentQuantity = maxQuantity;
    }
    
    updateQuantityDisplay();
    updateMaxStockInfo();
}

function updateVariantSelection(variantId) {
    // Update all labels
    document.querySelectorAll('.variant-label').forEach(label => {
        label.classList.remove('border-accent', 'bg-accent/5', 'border-2');
        label.classList.add('border-gray-200', 'dark:border-slate-600');
    });
    
    // Highlight selected
    const selectedLabel = document.querySelector(`label[for="variant-${variantId}"]`);
    if (selectedLabel) {
        selectedLabel.classList.add('border-accent', 'bg-accent/5', 'border-2');
        selectedLabel.classList.remove('border-gray-200', 'dark:border-slate-600');
    }
}

function increaseQuantity() {
    if (currentQuantity < maxQuantity) {
        currentQuantity++;
        updateQuantityDisplay();
        updateMaxStockInfo();
    } else {
        showToast('Stok tidak mencukupi', 'error');
    }
}

function decreaseQuantity() {
    if (currentQuantity > 1) {
        currentQuantity--;
        updateQuantityDisplay();
        updateMaxStockInfo();
    }
}

function updateQuantityDisplay() {
    document.getElementById('quantity-display').textContent = currentQuantity;
}

function updateMaxStockInfo() {
    const infoElement = document.getElementById('max-stock-info');
    if (maxQuantity < 10) {
        infoElement.textContent = `Stok tersisa: ${maxQuantity}`;
        infoElement.classList.remove('hidden');
    } else {
        infoElement.classList.add('hidden');
    }
}

function addToCart() {
    if (!currentProduct) {
        showToast('Produk tidak valid', 'error');
        return;
    }

    const payload = {
        product_id: currentProduct.product_id,
        quantity: currentQuantity,
        variant_id: selectedVariant
    };

    fetch('/ajax/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            cartData = data.cart;
            updateCartUI();
            showToast('Produk berhasil ditambahkan ke keranjang', 'success');
            hideAddToCartModal();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Gagal menambahkan produk', 'error');
    });
}

function hideAddToCartModal() {
    document.getElementById('add-to-cart-modal').classList.add('hidden');
    currentProduct = null;
    selectedVariant = null;
    currentQuantity = 1;
}

// Cart Management Functions
function loadCartData() {
    fetch('/ajax/cart')
        .then(response => response.json())
        .then(data => {
            cartData = data.cart;
            updateCartUI();
        })
        .catch(error => {
            console.error('Error loading cart:', error);
            showToast('Gagal memuat keranjang', 'error');
        });
}

function updateCartUI() {
    updateCartModal();
    updateCartBadge();
}

function updateCartModal() {
    const container = document.getElementById('cart-items-container');
    const itemCount = document.getElementById('cart-item-count');
    const subtotal = document.getElementById('cart-subtotal');
    const checkoutBtn = document.getElementById('checkout-btn');

    if (cartData.items.length === 0) {
        container.innerHTML = `
            <div class="text-center text-gray-500 dark:text-slate-400 py-12">
                <i class="fas fa-shopping-cart text-4xl mb-3"></i>
                <p class="text-lg mb-2">Keranjang kosong</p>
                <p class="text-sm mb-4">Yuk, tambahkan produk favoritmu!</p>
                <button onclick="toggleCart()" 
                        class="bg-accent text-white px-6 py-2 rounded-lg hover:bg-accent/90 transition">
                    Mulai Belanja
                </button>
            </div>
        `;
        checkoutBtn.disabled = true;
    } else {
        container.innerHTML = cartData.items.map(item => {
            const itemImage = item.product.images && item.product.images[0] ? 
                `/storage/${item.product.images[0].file_path}` : 
                '/images/placeholder.jpg';
                
            return `
            <div class="flex items-center space-x-3 py-4 border-b border-gray-200 dark:border-slate-700">
                <img src="${itemImage}" 
                     alt="${item.product.name}"
                     class="w-14 h-14 rounded-lg object-cover flex-shrink-0 bg-gray-100">
                <div class="flex-1 min-w-0">
                    <h4 class="font-medium text-primary dark:text-light text-sm leading-tight">${item.product.name}</h4>
                    ${item.variant ? `<p class="text-xs text-gray-600 dark:text-slate-400 mt-1">${item.variant.name}</p>` : ''}
                    <p class="text-accent font-semibold text-sm mt-1">
                        Rp ${formatPrice(item.price_snapshot)}
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="updateCartItem(${item.cart_item_id}, ${item.qty - 1})" 
                            class="w-8 h-8 rounded-full border border-gray-300 dark:border-slate-600 
                                   flex items-center justify-center text-gray-600 dark:text-slate-400 
                                   hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                        <i class="fas fa-minus text-xs"></i>
                    </button>
                    <span class="font-medium text-primary dark:text-light w-8 text-center">${item.qty}</span>
                    <button onclick="updateCartItem(${item.cart_item_id}, ${item.qty + 1})" 
                            class="w-8 h-8 rounded-full border border-gray-300 dark:border-slate-600 
                                   flex items-center justify-center text-gray-600 dark:text-slate-400 
                                   hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>
            </div>
            `;
        }).join('');
        checkoutBtn.disabled = false;
    }

    if (itemCount) itemCount.textContent = cartData.item_count;
    if (subtotal) subtotal.textContent = `Rp ${formatPrice(cartData.subtotal)}`;
}

function updateCartItem(cartItemId, newQuantity) {
    if (newQuantity <= 0) {
        removeFromCart(cartItemId);
        return;
    }

    fetch(`/ajax/cart/items/${cartItemId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            cartData = data.cart;
            updateCartUI();
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error updating item:', error);
        showToast('Gagal mengupdate item', 'error');
    });
}

function removeFromCart(cartItemId) {
    if (!confirm('Hapus produk dari keranjang?')) return;

    fetch(`/ajax/cart/items/${cartItemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            cartData = data.cart;
            updateCartUI();
            showToast('Produk dihapus dari keranjang', 'info');
        }
    })
    .catch(error => {
        console.error('Error removing item:', error);
        showToast('Gagal menghapus item', 'error');
    });
}

function updateCartBadge() {
    const cartCount = document.getElementById('cart-count');
    if (cartCount) {
        cartCount.textContent = cartData.item_count;
        // Add animation
        cartCount.classList.add('animate-pulse');
        setTimeout(() => cartCount.classList.remove('animate-pulse'), 300);
    }
}

function toggleCart() {
    const modal = document.getElementById('cart-modal');
    modal.classList.toggle('hidden');
    
    if (!modal.classList.contains('hidden')) {
        loadCartData();
    }
}

function proceedToCheckout() {
    if (cartData.items.length === 0) {
        showToast('Keranjang kosong', 'error');
        return;
    }
    window.location.href = '/user/orders/create';
}

// Utility Functions
function formatPrice(price) {
    return new Intl.NumberFormat('id-ID').format(price);
}

function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());

    const toast = document.createElement('div');
    toast.className = `custom-toast fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 transform translate-x-full transition-transform duration-300 shadow-lg ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    toast.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'}"></i>
            <span class="text-sm font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);

    // Animate in
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    
    // Animate out after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadCartData();
    
    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        const cartModal = document.getElementById('cart-modal');
        const addToCartModal = document.getElementById('add-to-cart-modal');
        const cartButton = document.getElementById('cart-button');
        
        if (cartModal && !cartModal.contains(e.target) && !cartButton?.contains(e.target)) {
            cartModal.classList.add('hidden');
        }
        
        if (addToCartModal && !addToCartModal.contains(e.target)) {
            hideAddToCartModal();
        }
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const cartModal = document.getElementById('cart-modal');
            const addToCartModal = document.getElementById('add-to-cart-modal');
            
            if (!cartModal.classList.contains('hidden')) {
                cartModal.classList.add('hidden');
            }
            
            if (!addToCartModal.classList.contains('hidden')) {
                hideAddToCartModal();
            }
        }
    });
});

// Export for global access
window.showAddToCartModal = showAddToCartModal;
window.hideAddToCartModal = hideAddToCartModal;
window.toggleCart = toggleCart;
window.loadCartData = loadCartData;