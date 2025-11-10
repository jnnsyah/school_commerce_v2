<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Academic\SchoolClass;
use App\Models\User\User;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductStatus;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductExtra;
use App\Models\Order\OrderItem;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    
    protected $fillable = [
        'class_id',
        'name',
        'sku',
        'description',
        'price',
        'category_id',
        'status_id',
        'rejection_reason',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    // Relationships
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function status()
    {
        return $this->belongsTo(ProductStatus::class, 'status_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function extras()
    {
        return $this->hasMany(ProductExtra::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // Helper Methods
    public function getPrimaryImage()
    {
        return $this->images()->where('is_primary', true)->first();
    }

    public function getStockQuantity()
    {
        // Total stock dari semua variants + product utama
        $variantStock = $this->variants()->sum('stock_at');
        return $variantStock;
    }

    public function getTotalSold()
    {
        return $this->orderItems()->sum('qty');
    }

    public function canBeOrdered()
    {
        return $this->isApproved() && $this->getStockQuantity() > 0;
    }

        /**
     * Check if product is pending approval
     */
    public function isPending()
    {
        return $this->status_id == ProductStatus::PENDING;
    }

    /**
     * Check if product is approved
     */
    public function isApproved()
    {
        return $this->status_id == ProductStatus::APPROVED;
    }

    /**
     * Check if product is rejected
     */
    public function isRejected()
    {
        return $this->status_id == ProductStatus::REJECTED;
    }

    /**
     * Scope for pending products
     */
    public function scopePending($query)
    {
        return $query->where('status_id', ProductStatus::PENDING);
    }

    /**
     * Scope for approved products
     */
    public function scopeApproved($query)
    {
        return $query->where('status_id', ProductStatus::APPROVED);
    }

    /**
     * Scope for rejected products
     */
    public function scopeRejected($query)
    {
        return $query->where('status_id', ProductStatus::REJECTED);
    }

    /**
     * Get products that need approval (for guru_pkwu)
     */
    public function scopeNeedsApproval($query)
    {
        return $query->where('status_id', ProductStatus::PENDING);
    }
}