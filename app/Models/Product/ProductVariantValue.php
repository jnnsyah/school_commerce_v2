<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductVariant;

class ProductVariantValue extends Model
{
    use HasFactory;

    protected $table = 'product_variant_values';
    
    protected $fillable = [
        'variant_id',
        'option_name',
        'option_value',
    ];

    // Relationships
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}