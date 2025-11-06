<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order\Cart;
use App\Models\Order\CartItem;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductExtra;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load(['items.product.images', 'items.variant', 'items.extras.extra']);

        return view('cart.index', compact('cart'));
    }

    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'variant_id' => 'nullable|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
            'extras' => 'nullable|array',
            'extras.*.id' => 'required|exists:product_extras,id',
            'extras.*.quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check if product is available
        if (!$product->canBeOrdered()) {
            return redirect()->back()->with('error', 'Product is not available for ordering.');
        }

        $cart = $this->getOrCreateCart();
        
        // Check if item already exists in cart
        $existingItem = $cart->items()->where('product_id', $product->product_id)
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($existingItem) {
            $existingItem->update([
                'qty' => $existingItem->qty + $request->quantity
            ]);
        } else {
            $cartItem = $cart->items()->create([
                'product_id' => $product->product_id,
                'variant_id' => $request->variant_id,
                'qty' => $request->quantity,
                'price_snapshot' => $this->getItemPrice($product, $request->variant_id),
            ]);

            // Add extras if any
            if ($request->has('extras')) {
                foreach ($request->extras as $extra) {
                    $extraProduct = ProductExtra::find($extra['id']);
                    if ($extraProduct && $extraProduct->isAvailable()) {
                        $cartItem->extras()->create([
                            'extra_id' => $extra['id'],
                            'qty' => $extra['quantity'],
                            'price_snapshot' => $extraProduct->price,
                        ]);
                    }
                }
            }
        }

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function updateItem(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if cart item belongs to user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->update(['qty' => $request->quantity]);

        return redirect()->back()->with('success', 'Cart updated successfully.');
    }

    public function removeItem(CartItem $cartItem)
    {
        // Check if cart item belongs to user
        if ($cartItem->cart->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();

        return redirect()->route('cart.index')->with('success', 'Cart cleared successfully.');
    }

    private function getOrCreateCart()
    {
        return Cart::firstOrCreate(['user_id' => auth()->id()]);
    }

    private function getItemPrice(Product $product, $variantId = null)
    {
        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            return $variant ? $variant->price : $product->price;
        }

        return $product->price;
    }
}