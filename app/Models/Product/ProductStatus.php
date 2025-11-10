<?php
// app/Models/Product/ProductStatus.php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStatus extends Model
{
    use HasFactory;

    protected $table = 'product_statuses';
    protected $primaryKey = 'id';

    // Status constants
    const PENDING = 1;
    const APPROVED = 2;
    const REJECTED = 3;

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    /**
     * Relationship with products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'status_id');
    }

    /**
     * Get status name by ID
     */
    public static function getName($statusId)
    {
        $statuses = [
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        ];

        return $statuses[$statusId] ?? 'Unknown';
    }
}