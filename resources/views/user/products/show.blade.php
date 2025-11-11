<!-- resources/views/user/products/show.blade.php -->
@extends('layouts.user-app')

@section('title', $product->name . ' - School Commerce')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-600 dark:text-slate-400 mb-6">
        <a href="{{ route('user.products.index') }}" class="hover:text-accent transition">Produk</a>
        <span class="text-gray-400">›</span>
        <span class="text-primary dark:text-light">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div class="space-y-4">
            <!-- Main Image -->
            <div class="bg-white dark:bg-secondary rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 overflow-hidden">
                <div class="aspect-square bg-gray-100 dark:bg-slate-800 flex items-center justify-center">
                    @if($product->images->count() > 0)
                        <img 
                            src="{{ asset('storage/' . $product->getPrimaryImage()->file_path) }}" 
                            alt="{{ $product->name }}"
                            class="w-full h-full object-cover transition-transform duration-500"
                            id="main-product-image"
                            loading="lazy"
                        >
                    @else
                        <div class="text-gray-400 dark:text-slate-600 text-center">
                            <i class="fas fa-image text-6xl mb-3"></i>
                            <p class="text-lg">No Image</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thumbnail Images -->
            @if($product->images->count() > 1)
            <div class="grid grid-cols-4 gap-3">
                @foreach($product->images as $image)
                <button 
                    onclick="changeMainImage('{{ asset('storage/' . $image->file_path) }}')"
                    class="aspect-square bg-white dark:bg-secondary rounded-lg border-2 border-gray-200 dark:border-slate-700 overflow-hidden hover:border-accent transition-all {{ $loop->first ? 'border-accent' : '' }}"
                >
                    <img 
                        src="{{ asset('storage/' . $image->file_path) }}" 
                        alt="{{ $product->name }} - Image {{ $loop->iteration }}"
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >
                </button>
                @endforeach
            </div>
            @endif
        </div>

        <!-- Product Info -->
        <div class="space-y-6">
            <!-- Product Header -->
            <div class="space-y-3">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h1 class="text-2xl lg:text-3xl font-bold text-primary dark:text-light leading-tight">
                            {{ $product->name }}
                        </h1>
                        <div class="flex items-center space-x-2 mt-2">
                            <span class="bg-accent/10 text-accent text-sm px-3 py-1 rounded-full font-medium">
                                {{ $product->category->name }}
                            </span>
                            <span class="bg-primary/10 text-primary dark:bg-slate-700 dark:text-light text-sm px-3 py-1 rounded-full">
                                {{ $product->class->grade->name }} {{ $product->class->major->short_name }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div class="flex items-baseline space-x-2">
                    <span class="text-3xl font-bold text-accent">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    @if($product->getTotalSold() > 0)
                    <span class="text-sm text-gray-600 dark:text-slate-400">
                        • Terjual: {{ $product->getTotalSold() }}
                    </span>
                    @endif
                </div>

                <!-- Stock Info -->
                @php
                    $stock = $product->getStockQuantity();
                    $hasStock = $stock > 0;
                    $isLowStock = $hasStock && $stock <= 5;
                @endphp
                
                <div class="flex items-center space-x-2 text-sm">
                    @if($hasStock)
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span class="text-green-600 dark:text-green-400 font-medium">Tersedia</span>
                        @if($isLowStock)
                            <span class="text-orange-600 dark:text-orange-400">• Stok terbatas: {{ $stock }}</span>
                        @endif
                    @else
                        <i class="fas fa-times-circle text-red-500"></i>
                        <span class="text-red-600 dark:text-red-400 font-medium">Stok Habis</span>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-3">
                <h3 class="text-lg font-semibold text-primary dark:text-light">Deskripsi Produk</h3>
                <div class="prose prose-sm max-w-none text-gray-700 dark:text-slate-300 leading-relaxed">
                    {!! nl2br(e($product->description)) !!}
                </div>
            </div>

            <!-- Variants Section -->
            @if($product->variants->count() > 0)
            <div class="space-y-3">
                <h3 class="text-lg font-semibold text-primary dark:text-light">Pilihan Varian</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="variant-options">
                    @foreach($product->variants as $variant)
                    <div class="variant-option {{ $variant->stock_at <= 0 ? 'opacity-50' : '' }}">
                        <input 
                            type="radio" 
                            name="variant" 
                            value="{{ $variant->id }}" 
                            id="variant-{{ $variant->id }}"
                            {{ $variant->stock_at <= 0 ? 'disabled' : '' }}
                            class="hidden variant-radio"
                            data-price="{{ $variant->price }}"
                            data-stock="{{ $variant->stock_at }}"
                        >
                        <label 
                            for="variant-{{ $variant->id }}" 
                            class="block p-4 border-2 border-gray-200 dark:border-slate-700 rounded-xl cursor-pointer transition-all hover:border-accent variant-label {{ $variant->stock_at <= 0 ? 'cursor-not-allowed' : '' }}"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-medium text-primary dark:text-light block">{{ $variant->name }}</span>
                                    @if($variant->price != $product->price)
                                    <span class="text-accent font-semibold text-sm">
                                        + Rp {{ number_format($variant->price - $product->price, 0, ',', '.') }}
                                    </span>
                                    @endif
                                </div>
                                <div class="text-sm {{ $variant->stock_at <= 0 ? 'text-red-500' : 'text-gray-500 dark:text-slate-400' }}">
                                    {{ $variant->stock_at <= 0 ? 'Stok Habis' : 'Stok: ' . $variant->stock_at }}
                                </div>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Quantity & Add to Cart -->
            <div class="space-y-4 pt-4 border-t border-gray-200 dark:border-slate-700">
                <!-- Quantity Selector -->
                <div class="flex items-center justify-between">
                    <span class="text-lg font-semibold text-primary dark:text-light">Jumlah</span>
                    <div class="flex items-center space-x-3">
                        <button 
                            onclick="decreaseQuantity()" 
                            class="w-12 h-12 rounded-full border border-gray-300 dark:border-slate-600 flex items-center justify-center text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            id="decrease-btn"
                        >
                            <i class="fas fa-minus"></i>
                        </button>
                        <span id="quantity-display" class="text-xl font-bold text-primary dark:text-light w-12 text-center">1</span>
                        <button 
                            onclick="increaseQuantity()" 
                            class="w-12 h-12 rounded-full border border-gray-300 dark:border-slate-600 flex items-center justify-center text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                            id="increase-btn"
                        >
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Current Selection Info -->
                <div id="selection-info" class="text-sm text-gray-600 dark:text-slate-400 space-y-1 hidden">
                    <div id="selected-variant"></div>
                    <div id="stock-info"></div>
                    <div id="total-price" class="font-semibold text-accent"></div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4 pt-4">
                    @if($hasStock)
                    <button 
                        onclick="addToCartFromDetail()"
                        class="flex-1 bg-accent text-white py-4 px-6 rounded-xl font-semibold hover:bg-accent/90 transition shadow-sm hover:shadow-md flex items-center justify-center space-x-2"
                        id="add-to-cart-btn"
                    >
                        <i class="fas fa-cart-plus"></i>
                        <span>Tambah ke Keranjang</span>
                    </button>
                    @else
                    <button 
                        disabled
                        class="flex-1 bg-gray-300 text-gray-500 py-4 px-6 rounded-xl font-semibold cursor-not-allowed flex items-center justify-center space-x-2"
                    >
                        <i class="fas fa-ban"></i>
                        <span>Stok Habis</span>
                    </button>
                    @endif
                    
                    <button 
                        onclick="toggleCart()"
                        class="w-14 h-14 bg-primary dark:bg-secondary text-white dark:text-light border border-gray-300 dark:border-slate-600 rounded-xl hover:bg-primary/90 dark:hover:bg-slate-700 transition flex items-center justify-center relative"
                    >
                        <i class="fas fa-shopping-cart text-lg"></i>
                        <span id="cart-badge" class="absolute -top-1 -right-1 bg-accent text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products Section -->
    @if($relatedProducts->count() > 0)
    <div class="mt-16 pt-8 border-t border-gray-200 dark:border-slate-700">
        <h2 class="text-2xl font-bold text-primary dark:text-light mb-6">Produk Serupa</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($relatedProducts as $relatedProduct)
                @include('components.user.product-card', ['product' => $relatedProduct])
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
// Product Detail JavaScript
let selectedVariantId = null;
let currentQuantity = 1;
let maxQuantity = {{ $product->getStockQuantity() }};
let basePrice = {{ $product->price }};

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateQuantityControls();
    setupVariantSelection();
    loadCartBadge();
});

// Image Gallery
function changeMainImage(imageUrl) {
    document.getElementById('main-product-image').src = imageUrl;
    
    // Update active thumbnail
    document.querySelectorAll('[onclick*="changeMainImage"]').forEach(btn => {
        btn.classList.remove('border-accent');
        btn.classList.add('border-gray-200', 'dark:border-slate-700');
    });
    event.target.closest('button').classList.add('border-accent');
    event.target.closest('button').classList.remove('border-gray-200', 'dark:border-slate-700');
}

// Variant Selection
function setupVariantSelection() {
    const variantRadios = document.querySelectorAll('.variant-radio');
    
    variantRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            selectedVariantId = this.value;
            const variantPrice = parseInt(this.dataset.price);
            const variantStock = parseInt(this.dataset.stock);
            
            // Update max quantity based on variant stock
            maxQuantity = variantStock;
            
            // Update UI
            updateVariantSelection(this);
            updateSelectionInfo(variantPrice, variantStock);
            updateQuantityControls();
        });
    });

    // Auto-select first available variant
    const firstAvailable = document.querySelector('.variant-radio:not(:disabled)');
    if (firstAvailable) {
        firstAvailable.checked = true;
        firstAvailable.dispatchEvent(new Event('change'));
    }
}

function updateVariantSelection(selectedRadio) {
    // Update all labels
    document.querySelectorAll('.variant-label').forEach(label => {
        label.classList.remove('border-accent', 'bg-accent/5');
        label.classList.add('border-gray-200', 'dark:border-slate-700');
    });
    
    // Highlight selected
    const selectedLabel = selectedRadio.nextElementSibling;
    selectedLabel.classList.add('border-accent', 'bg-accent/5');
    selectedLabel.classList.remove('border-gray-200', 'dark:border-slate-700');
}

function updateSelectionInfo(variantPrice, variantStock) {
    const selectionInfo = document.getElementById('selection-info');
    const selectedVariantEl = document.getElementById('selected-variant');
    const stockInfoEl = document.getElementById('stock-info');
    const totalPriceEl = document.getElementById('total-price');
    
    const selectedVariant = document.querySelector(`label[for="variant-${selectedVariantId}"] span:first-child`).textContent;
    const totalPrice = variantPrice * currentQuantity;
    
    selectedVariantEl.textContent = `Varian: ${selectedVariant}`;
    stockInfoEl.textContent = `Stok tersisa: ${variantStock}`;
    totalPriceEl.textContent = `Total: Rp ${formatPrice(totalPrice)}`;
    
    selectionInfo.classList.remove('hidden');
}

// Quantity Controls
function increaseQuantity() {
    if (currentQuantity < maxQuantity) {
        currentQuantity++;
        updateQuantityDisplay();
        updateSelectionInfo();
    } else {
        showToast('Stok tidak mencukupi', 'error');
    }
}

function decreaseQuantity() {
    if (currentQuantity > 1) {
        currentQuantity--;
        updateQuantityDisplay();
        updateSelectionInfo();
    }
}

function updateQuantityDisplay() {
    document.getElementById('quantity-display').textContent = currentQuantity;
}

function updateQuantityControls() {
    const decreaseBtn = document.getElementById('decrease-btn');
    const increaseBtn = document.getElementById('increase-btn');
    
    decreaseBtn.disabled = currentQuantity <= 1;
    increaseBtn.disabled = currentQuantity >= maxQuantity;
}

function updateSelectionInfo() {
    if (!selectedVariantId) return;
    
    const selectedRadio = document.querySelector(`.variant-radio[value="${selectedVariantId}"]`);
    const variantPrice = parseInt(selectedRadio.dataset.price);
    const variantStock = parseInt(selectedRadio.dataset.stock);
    
    const totalPriceEl = document.getElementById('total-price');
    const totalPrice = variantPrice * currentQuantity;
    totalPriceEl.textContent = `Total: Rp ${formatPrice(totalPrice)}`;
}

// Add to Cart from Detail Page
function addToCartFromDetail() {
    const payload = {
        product_id: {{ $product->product_id }},
        quantity: currentQuantity,
        variant_id: selectedVariantId
    };

    fetch('/ajax/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCsrfToken()
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Produk berhasil ditambahkan ke keranjang!', 'success');
            updateCartBadge(data.cart.item_count);
            
            // Show cart modal after adding
            setTimeout(() => {
                toggleCart();
            }, 1000);
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Gagal menambahkan produk', 'error');
    });
}

// Cart Badge
function loadCartBadge() {
    fetch('/ajax/cart')
        .then(response => response.json())
        .then(data => {
            updateCartBadge(data.cart.item_count);
        })
        .catch(error => console.error('Error loading cart:', error));
}

function updateCartBadge(count) {
    const cartBadge = document.getElementById('cart-badge');
    if (cartBadge) {
        cartBadge.textContent = count;
        cartBadge.classList.toggle('hidden', count === 0);
    }
}

// Utility Functions
function formatPrice(price) {
    return new Intl.NumberFormat('id-ID').format(price);
}

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

function showToast(message, type = 'info') {
    // Simple toast implementation
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg ${
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
    setTimeout(() => toast.remove(), 3000);
}
</script>

<style>
.variant-label {
    transition: all 0.2s ease;
}

.variant-label:hover:not(.cursor-not-allowed) {
    transform: translateY(-2px);
}

#main-product-image {
    max-height: 500px;
    object-fit: contain;
}

@media (max-width: 768px) {
    #main-product-image {
        max-height: 300px;
    }
}

.prose {
    line-height: 1.6;
}

.prose p {
    margin-bottom: 1em;
}
</style>
@endsection