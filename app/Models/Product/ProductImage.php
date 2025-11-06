<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_images';
    
    protected $fillable = [
        'product_id',
        'variant_id',
        'file_path',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    // Helper Methods
    public function getImageUrl()
    {
        return asset('storage/' . $this->file_path);
    }
}