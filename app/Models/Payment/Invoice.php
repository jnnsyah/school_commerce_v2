<?php
namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order\Order;
use App\Models\Payment\InvoiceStatus;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';
    
    protected $fillable = [
        'order_id',
        'invoice_number',
        'status_id',
        'issued_at',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function status()
    {
        return $this->belongsTo(InvoiceStatus::class, 'status_id');
    }

    public function isOverdue()
    {
        return $this->due_date->isPast() && $this->status_id == InvoiceStatus::PENDING;
    }
}