<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;

class StockProductCache extends Model
{
    use HasFactory;

    protected $table = 'stock_product_cache';
    protected $primaryKey = 'product_id';
    
    protected $fillable = [
        'product_id',
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
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}