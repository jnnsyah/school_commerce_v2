<?php
namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment\Payment;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $primaryKey = 'method_id';
    
    protected $fillable = ['name', 'provider', 'is_active'];

    public function payments()
    {
        return $this->hasMany(Payment::class, 'method_id');
    }

    // Constants
    const QRIS = 1;
    const CASH = 2;
    const TRANSFER = 3;
}