<!-- resources/views/components/user/cart-modal.blade.php -->
<div id="cart-modal" class="cart-modal fixed inset-0 z-50 hidden">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50" onclick="toggleCart()"></div>
    
    <!-- Modal Content -->
    <div class="absolute bottom-0 left-0 right-0 bg-white dark:bg-secondary rounded-t-2xl max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-slate-700 sticky top-0 bg-white dark:bg-secondary">
            <h2 class="text-lg font-bold text-primary dark:text-light flex items-center space-x-2">
                <i class="fas fa-shopping-cart text-accent"></i>
                <span>Keranjang</span>
                <span id="cart-item-count" class="bg-accent text-white text-xs px-2 py-1 rounded-full">0</span>
            </h2>
            <button onclick="toggleCart()" class="text-gray-500 hover:text-gray-700 dark:text-slate-400">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-4" id="cart-items-container">
            <!-- Items will be loaded here -->
        </div>

        <!-- Footer -->
        <div class="border-t border-gray-200 dark:border-slate-700 p-4 bg-white dark:bg-secondary sticky bottom-0">
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="font-semibold text-primary dark:text-light">Subtotal:</span>
                    <span id="cart-subtotal" class="font-bold text-accent text-lg">Rp 0</span>
                </div>
                
                <button id="checkout-btn" 
                        onclick="proceedToCheckout()"
                        class="w-full bg-accent text-white py-4 rounded-xl font-semibold hover:bg-accent/90 
                               transition shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed
                               flex items-center justify-center space-x-2"
                        disabled>
                    <i class="fas fa-credit-card"></i>
                    <span>Checkout Sekarang</span>
                </button>
                
                <button onclick="toggleCart()"
                        class="w-full py-3 text-gray-600 dark:text-slate-400 hover:text-gray-800 
                               dark:hover:text-slate-300 transition text-sm">
                    Lanjut Belanja
                </button>
            </div>
        </div>
    </div>
</div>