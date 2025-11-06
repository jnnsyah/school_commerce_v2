<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inventory\StockProduct;
use App\Models\Inventory\StockProductVariant;
use App\Models\Inventory\StockProductExtra;

class StockReferenceType extends Model
{
    use HasFactory;

    protected $table = 'stock_references_types';
    protected $primaryKey = 'type_id';
    
    protected $fillable = [
        'code',
        'description',
    ];

    // Relationships
    public function stockProducts()
    {
        return $this->hasMany(StockProduct::class, 'ref_type_id');
    }

    public function stockProductVariants()
    {
        return $this->hasMany(StockProductVariant::class, 'ref_type_id');
    }

    public function stockProductExtras()
    {
        return $this->hasMany(StockProductExtra::class, 'ref_type_id');
    }

    // Constants for reference types
    const ORDER = 1;
    const ADJUSTMENT = 2;
    const RETURN = 3;
    const INITIAL = 4;
}