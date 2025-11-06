<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use App\Models\Order\CartItem;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts';
    protected $primaryKey = 'cart_id';
    
    protected $fillable = [
        'user_id',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id');
    }

    // Helper Methods
    public function getTotalItems()
    {
        return $this->items()->count();
    }

    public function getSubtotal()
    {
        return $this->items->sum(function($item) {
            return $item->getSubtotal();
        });
    }

    public function isEmpty()
    {
        return $this->items()->count() === 0;
    }

    public function clear()
    {
        $this->items()->delete();
    }
}