<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\ProductExtra;

class StockProductExtraCache extends Model
{
    use HasFactory;

    protected $table = 'stock_product_extra_cache';
    protected $primaryKey = 'extra_id';
    
    protected $fillable = [
        'extra_id',
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
    public function extra()
    {
        return $this->belongsTo(ProductExtra::class, 'extra_id');
    }
}