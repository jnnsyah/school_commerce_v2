<?php
namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\Order;
use App\Models\Payment\PaymentStatus;
use App\Models\Payment\PaymentMethod;
use App\Models\User\User;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';
    protected $primaryKey = 'payment_id';
    
    protected $fillable = [
        'order_id',
        'status_id',
        'method_id',
        'reference_id',
        'amount',
        'snap_token',
        'approved_by',
        'approved_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function status()
    {
        return $this->belongsTo(PaymentStatus::class, 'status_id');
    }

    public function method()
    {
        return $this->belongsTo(PaymentMethod::class, 'method_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isPending()
    {
        return $this->status_id == PaymentStatus::PENDING;
    }

    public function isPaid()
    {
        return $this->status_id == PaymentStatus::PAID;
    }

    public function isExpired()
    {
        return $this->status_id == PaymentStatus::EXPIRED;
    }
}