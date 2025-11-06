<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;
use App\Models\Product\ProductStatus;
use App\Models\Product\ProductVariantValue;
use App\Models\Inventory\StockProductVariant;
use App\Models\Order\OrderItem;

class ProductVariant extends Model
{
    use HasFactory;

    protected $table = 'product_variants';
    
    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'price',
        'status_id',
        'stock_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_at' => 'integer',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function status()
    {
        return $this->belongsTo(ProductStatus::class, 'status_id');
    }

    public function variantValues()
    {
        return $this->hasMany(ProductVariantValue::class, 'variant_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockProductVariant::class, 'variant_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }

    // Helper Methods
    public function getVariantOptions()
    {
        return $this->variantValues->map(function($value) {
            return "{$value->option_name}: {$value->option_value}";
        })->implode(', ');
    }

    public function getCurrentStock()
    {
        return $this->stock_at;
    }

    public function reduceStock($quantity)
    {
        $this->decrement('stock_at', $quantity);
    }

    public function increaseStock($quantity)
    {
        $this->increment('stock_at', $quantity);
    }
}