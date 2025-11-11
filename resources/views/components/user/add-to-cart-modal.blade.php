<!-- resources/views/components/user/add-to-cart-modal.blade.php -->
<div id="add-to-cart-modal" class="fixed inset-0 z-50 hidden">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="hideAddToCartModal()"></div>
    
    <!-- Modal Content -->
    <div class="absolute bottom-0 left-0 right-0 bg-white dark:bg-secondary rounded-t-2xl max-h-[85vh] overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-700 sticky top-0 bg-white dark:bg-secondary">
            <h2 class="text-lg font-bold text-primary dark:text-light">Tambah ke Keranjang</h2>
            <button onclick="hideAddToCartModal()" class="text-gray-500 hover:text-gray-700 dark:text-slate-400">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Product Info -->
        <div class="p-4 border-b border-gray-200 dark:border-slate-700" id="modal-product-info">
            <!-- Will be filled by JavaScript -->
        </div>

        <!-- Variant Selection -->
        <div class="p-4 border-b border-gray-200 dark:border-slate-700 hidden" id="variant-section">
            <h3 class="font-semibold text-primary dark:text-light mb-3">Pilihan Variant</h3>
            <div id="variant-options" class="space-y-3">
                <!-- Variant options will be loaded here -->
            </div>
        </div>

        <!-- Quantity Selection -->
        <div class="p-4 border-b border-gray-200 dark:border-slate-700">
            <h3 class="font-semibold text-primary dark:text-light mb-3">Jumlah</h3>
            <div class="flex items-center justify-between max-w-xs mx-auto">
                <button onclick="decreaseQuantity()" 
                        class="w-10 h-10 rounded-full border border-gray-300 dark:border-slate-600 
                               flex items-center justify-center text-gray-600 dark:text-slate-400 
                               hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                    <i class="fas fa-minus"></i>
                </button>
                
                <span id="quantity-display" class="text-xl font-bold text-primary dark:text-light mx-4">1</span>
                
                <button onclick="increaseQuantity()" 
                        class="w-10 h-10 rounded-full border border-gray-300 dark:border-slate-600 
                               flex items-center justify-center text-gray-600 dark:text-slate-400 
                               hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            <p id="max-stock-info" class="text-xs text-gray-500 dark:text-slate-400 text-center mt-2"></p>
        </div>

        <!-- Action Buttons -->
        <div class="p-4 sticky bottom-0 bg-white dark:bg-secondary border-t border-gray-200 dark:border-slate-700">
            <div class="flex space-x-3">
                <button onclick="hideAddToCartModal()"
                        class="flex-1 py-3 px-4 border border-gray-300 dark:border-slate-600 
                               rounded-lg text-primary dark:text-light hover:bg-gray-50 dark:hover:bg-slate-700 
                               transition font-medium">
                    Batal
                </button>
                <button onclick="addToCart()"
                        class="flex-1 py-3 px-4 bg-accent text-white rounded-lg hover:bg-accent/90 
                               transition font-medium shadow-sm">
                    Tambah ke Keranjang
                </button>
            </div>
        </div>
    </div>
</div>