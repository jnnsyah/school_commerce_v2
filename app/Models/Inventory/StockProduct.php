<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product;
use App\Models\User\User;
use App\Models\Inventory\StockReferenceType;

class StockProduct extends Model
{
    use HasFactory;

    protected $table = 'stock_product';
    protected $primaryKey = 'stock_id';
    
    protected $fillable = [
        'product_id',
        'ref_type_id',
        'ref_id',
        'qty',
        'note',
        'user_id',
    ];

    protected $casts = [
        'qty' => 'integer',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function referenceType()
    {
        return $this->belongsTo(StockReferenceType::class, 'ref_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}