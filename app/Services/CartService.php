<?php

namespace App\Services;

use App\Models\Order\Cart;
use App\Models\Order\CartItem;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductExtra;

class CartService
{
    public function getCart($userId)
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    public function addToCart($userId, $productId, $variantId = null, $quantity = 1, $extras = [])
    {
        $cart = $this->getCart($userId);
        $product = Product::findOrFail($productId);

        // Check product availability
        if (!$product->canBeOrdered()) {
            throw new \Exception('Product is not available for ordering');
        }

        // Check variant availability if specified
        if ($variantId) {
            $variant = ProductVariant::where('id', $variantId)
                ->where('product_id', $productId)
                ->firstOrFail();

            if ($variant->getCurrentStock() < $quantity) {
                throw new \Exception('Insufficient stock for selected variant');
            }
        }

        // Find existing cart item
        $existingItem = $cart->items()
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($existingItem) {
            // Update quantity
            $newQuantity = $existingItem->qty + $quantity;
            
            // Check stock again for updated quantity
            if ($variantId && $variant->getCurrentStock() < $newQuantity) {
                throw new \Exception('Insufficient stock for updated quantity');
            }

            $existingItem->update(['qty' => $newQuantity]);
            $cartItem = $existingItem;
        } else {
            // Create new cart item
            $price = $variantId ? $variant->price : $product->price;
            
            $cartItem = $cart->items()->create([
                'product_id' => $productId,
                'variant_id' => $variantId,
                'qty' => $quantity,
                'price_snapshot' => $price,
            ]);
        }

        // Add extras
        $this->addExtrasToCartItem($cartItem, $extras);

        return $cartItem;
    }

    private function addExtrasToCartItem($cartItem, $extras)
    {
        foreach ($extras as $extraData) {
            $extra = ProductExtra::find($extraData['id']);
            
            if (!$extra || !$extra->isAvailable()) {
                throw new \Exception("Extra {$extraData['id']} is not available");
            }

            if ($extra->stock_cache < $extraData['quantity']) {
                throw new \Exception("Insufficient stock for {$extra->name}");
            }

            $cartItem->extras()->create([
                'extra_id' => $extra->id,
                'qty' => $extraData['quantity'],
                'price_snapshot' => $extra->price,
            ]);
        }
    }

    public function updateCartItemQuantity($cartItemId, $quantity)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        
        if ($quantity <= 0) {
            $cartItem->delete();
            return null;
        }

        // Check stock availability
        if ($cartItem->variant_id) {
            $variant = $cartItem->variant;
            if ($variant->getCurrentStock() < $quantity) {
                throw new \Exception('Insufficient stock for selected variant');
            }
        }

        $cartItem->update(['qty' => $quantity]);
        return $cartItem;
    }

    public function removeFromCart($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->delete();
        
        return true;
    }

    public function clearCart($userId)
    {
        $cart = $this->getCart($userId);
        $cart->items()->delete();
        
        return true;
    }

    public function getCartSummary($userId)
    {
        $cart = $this->getCart($userId);
        $cart->load(['items.product', 'items.variant', 'items.extras.extra']);

        return [
            'item_count' => $cart->getTotalItems(),
            'subtotal' => $cart->getSubtotal(),
            'items' => $cart->items,
        ];
    }
}