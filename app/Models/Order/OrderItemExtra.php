<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\OrderItem;
use App\Models\Product\ProductExtra;

class OrderItemExtra extends Model
{
    use HasFactory;

    protected $table = 'order_item_extras';
    
    protected $fillable = [
        'order_item_id',
        'extra_id',
        'qty',
        'price',
    ];

    protected $casts = [
        'qty' => 'integer',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function extra()
    {
        return $this->belongsTo(ProductExtra::class, 'extra_id');
    }

    // Helper Methods
    public function getSubtotal()
    {
        return $this->price * $this->qty;
    }
}