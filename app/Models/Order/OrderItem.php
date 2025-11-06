<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\Order;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Order\OrderItemExtra;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';
    
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'qty',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
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
        return $this->hasMany(OrderItemExtra::class, 'order_item_id');
    }

    // Helper Methods
    public function getProductName()
    {
        if ($this->variant) {
            return "{$this->product->name} - {$this->variant->name}";
        }
        return $this->product->name;
    }

    public function getTotalWithExtras()
    {
        $extrasTotal = $this->extras->sum(function($extra) {
            return $extra->price * $extra->qty;
        });

        return $this->subtotal + $extrasTotal;
    }
}