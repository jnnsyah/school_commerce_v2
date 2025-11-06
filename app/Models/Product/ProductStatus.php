<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;

class ProductStatus extends Model
{
    use HasFactory;

    protected $table = 'product_statuses';
    
    protected $fillable = [
        'name',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class, 'status_id');
    }

    // Constants for status IDs
    const PENDING = 1;
    const APPROVED = 2;
    const REJECTED = 3;
}