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

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}