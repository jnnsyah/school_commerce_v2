<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User\User;
use App\Models\Order\OrderStatus;
use App\Models\Order\OrderItem;
use App\Models\Order\TransactionLog;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    
    protected $fillable = [
        'invoice_number',
        'user_id',
        'total_amount',
        'status_id',
        'customer_notes',
        'completed_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function transactionLogs()
    {
        return $this->hasMany(TransactionLog::class, 'order_id');
    }

    // Helper Methods
    public function isPending()
    {
        return $this->status_id == OrderStatus::PENDING;
    }

    public function isPaid()
    {
        return $this->status_id == OrderStatus::PAID;
    }

    public function isCompleted()
    {
        return $this->status_id == OrderStatus::COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status_id == OrderStatus::CANCELLED;
    }

    public function getItemsCount()
    {
        return $this->items()->count();
    }

    public function canBeCancelled()
    {
        return $this->isPending() || $this->isPaid();
    }

    public function markAsCompleted()
    {
        $this->update([
            'status_id' => OrderStatus::COMPLETED,
            'completed_at' => now(),
        ]);
    }
}