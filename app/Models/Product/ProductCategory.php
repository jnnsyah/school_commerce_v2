<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_categories';
    
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    // Helper Methods
    public function getActiveProductsCount()
    {
        return $this->products()->whereHas('status', function($query) {
            $query->where('name', 'approved');
        })->count();
    }
}