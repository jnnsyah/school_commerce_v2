<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\Cart;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Order\CartItemExtra;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';
    protected $primaryKey = 'cart_item_id';
    
    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'qty',
        'price_snapshot',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price_snapshot' => 'decimal:2',
    ];

    // Relationships
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function extras()
    {
        return $this->hasMany(CartItemExtra::class, 'cart_item_id');
    }

    // Helper Methods
    public function getSubtotal()
    {
        $extrasTotal = $this->extras->sum(function($extra) {
            return $extra->price_snapshot * $extra->qty;
        });

        return ($this->price_snapshot * $this->qty) + $extrasTotal;
    }

    public function getProductName()
    {
        if ($this->variant) {
            return "{$this->product->name} - {$this->variant->name}";
        }
        return $this->product->name;
    }
}