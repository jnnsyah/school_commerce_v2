<?php
namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment\Invoice;

class InvoiceStatus extends Model
{
    use HasFactory;

    protected $table = 'invoice_statuses';
    protected $primaryKey = 'status_id';
    
    protected $fillable = ['name'];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'status_id');
    }

    // Constants
    const PENDING = 1;
    const PAID = 2;
    const OVERDUE = 3;
    const CANCELLED = 4;
}