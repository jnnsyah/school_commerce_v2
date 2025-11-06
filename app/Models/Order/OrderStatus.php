<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\Order;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_statuses';
    protected $primaryKey = 'status_id';
    
    protected $fillable = [
        'name',
    ];

    // Relationships
    public function orders()
    {
        return $this->hasMany(Order::class, 'status_id');
    }

    // Constants for status IDs
    const PENDING = 1;
    const PAID = 2;
    const PROCESSING = 3;
    const COMPLETED = 4;
    const CANCELLED = 5;
}