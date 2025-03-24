<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_transaction_id',
        'type',
        'amount',
        'description',
    ];

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }
}
