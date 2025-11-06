<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductVariant;

class StockProductVariantCache extends Model
{
    use HasFactory;

    protected $table = 'stock_product_variant_cache';
    protected $primaryKey = 'variant_id';
    
    protected $fillable = [
        'variant_id',
        'total_stock',
        'updated_at',
    ];

    protected $casts = [
        'total_stock' => 'integer',
        'updated_at' => 'datetime',
    ];

    public $incrementing = false;
    public $timestamps = false;

    // Relationships
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}