<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;
use App\Models\Inventory\StockProductExtra;
use App\Models\Order\OrderItemExtra;

class ProductExtra extends Model
{
    use HasFactory;

    protected $table = 'product_extras';
    
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'max_qty',
        'stock_cache',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'max_qty' => 'integer',
        'stock_cache' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockProductExtra::class, 'extra_id');
    }

    public function orderItemExtras()
    {
        return $this->hasMany(OrderItemExtra::class, 'extra_id');
    }

    // Helper Methods
    public function isAvailable()
    {
        return $this->is_active && $this->stock_cache > 0;
    }

    public function reduceStock($quantity)
    {
        $this->decrement('stock_cache', $quantity);
    }

    public function increaseStock($quantity)
    {
        $this->increment('stock_cache', $quantity);
    }
}