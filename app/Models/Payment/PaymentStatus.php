<?php
namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment\Payment;

class PaymentStatus extends Model
{
    use HasFactory;

    protected $table = 'payment_statuses';
    protected $primaryKey = 'status_id';
    
    protected $fillable = ['name'];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'status_id');
    }

    // Constants
    const PENDING = 1;
    const PAID = 2;
    const FAILED = 3;
    const EXPIRED = 4;
}