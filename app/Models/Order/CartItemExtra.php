<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\CartItem;
use App\Models\Product\ProductExtra;

class CartItemExtra extends Model
{
    use HasFactory;

    protected $table = 'cart_item_extras';
    
    protected $fillable = [
        'cart_item_id',
        'extra_id',
        'qty',
        'price_snapshot',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price_snapshot' => 'decimal:2',
    ];

    // Relationships
    public function cartItem()
    {
        return $this->belongsTo(CartItem::class, 'cart_item_id');
    }

    public function extra()
    {
        return $this->belongsTo(ProductExtra::class, 'extra_id');
    }

    // Helper Methods
    public function getSubtotal()
    {
        return $this->price_snapshot * $this->qty;
    }
}