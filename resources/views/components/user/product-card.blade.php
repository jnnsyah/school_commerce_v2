<!-- resources/views/components/user/product-card.blade.php -->
@props(['product'])

@php
    $stock = $product->getStockQuantity();
    $hasStock = $stock > 0;
    $isLowStock = $hasStock && $stock <= 5;
@endphp

<div class="bg-white dark:bg-secondary rounded-xl shadow-sm border border-gray-100 dark:border-slate-700 
            overflow-hidden hover:shadow-md transition-all duration-300 active:scale-95">
    
    <!-- Product Image with Lazy Loading -->
    <div class="relative aspect-[4/3] bg-gray-50 dark:bg-slate-800 overflow-hidden">
        @if($product->images->count() > 0)
            <img 
                src="{{ asset('storage/' . $product->images->first()->file_path) }}" 
                alt="{{ $product->name }}"
                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105 lazy-load"
                loading="lazy"
            >
        @else
            <div class="w-full h-full flex items-center justify-center text-gray-300 dark:text-slate-600">
                <i class="fas fa-image text-2xl"></i>
            </div>
        @endif
        
        <!-- Seller Badge -->
        <div class="absolute top-2 left-2">
            <span class="bg-black/70 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                {{ $product->class->grade->name }} {{ $product->class->major->short_name }}
            </span>
        </div>

        <!-- Stock Indicator -->
        @if(!$hasStock)
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-medium">
                    Stok Habis
                </span>
            </div>
        @elseif($isLowStock)
            <div class="absolute top-2 right-2">
                <span class="bg-orange-500 text-white text-xs px-2 py-1 rounded-full">
                    Stok: {{ $stock }}
                </span>
            </div>
        @endif
    </div>

    <!-- Product Info -->
    <div class="p-3">
        <h3 class="font-semibold text-gray-900 dark:text-light text-sm mb-1 line-clamp-2 leading-tight">
            {{ $product->name }}
        </h3>
        
        <p class="text-xs text-gray-600 dark:text-slate-400 mb-2 line-clamp-2 leading-relaxed">
            {{ Str::limit($product->description, 50) }}
        </p>

        <div class="flex items-center justify-between">
            <div>
                <span class="text-base font-bold text-accent block">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
                @if($product->getTotalSold() > 0)
                <span class="text-xs text-gray-500 dark:text-slate-500">
                    Terjual: {{ $product->getTotalSold() }}
                </span>
                @endif
            </div>
            
            <!-- Add to Cart Button -->
            @if($hasStock)
            <button onclick="showAddToCartModal({{ $product->product_id }})"
                    class="bg-accent text-white p-2 rounded-lg hover:bg-accent/90 transition-all 
                           duration-200 active:scale-95 shadow-sm hover:shadow-md">
                <i class="fas fa-plus text-sm"></i>
            </button>
            @else
            <button disabled
                    class="bg-gray-300 text-gray-500 p-2 rounded-lg cursor-not-allowed">
                <i class="fas fa-ban text-sm"></i>
            </button>
            @endif
        </div>
    </div>
</div>