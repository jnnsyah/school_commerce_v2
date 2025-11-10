<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\Order;
use App\Models\Order\OrderStatus;
use App\Models\User\User;

class TransactionLog extends Model
{
    use HasFactory;

    protected $table = 'transaction_log';
    protected $primaryKey = 'log_id';
    
    protected $fillable = [
        'order_id',
        'status_id',
        'note',
        'created_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id', 'status_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Helper Methods
    public function getStatusName()
    {
        return $this->status->name ?? 'Unknown';
    }

    public function getCreatorName()
    {
        return $this->createdBy->name ?? 'System';
    }

    public function getFormattedCreatedAt()
    {
        return $this->created_at->format('d M Y, H:i');
    }

    public function getTimeAgo()
    {
        return $this->created_at->diffForHumans();
    }

    // Scope for recent logs
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Scope by order
    public function scopeByOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    // Scope by status
    public function scopeByStatus($query, $statusId)
    {
        return $query->where('status_id', $statusId);
    }

    // Scope by creator
    public function scopeByCreator($query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}