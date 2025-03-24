<?php

namespace App\Models;

use App\Models\User;
use App\Models\PaymentTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_transaction_id',
        'amount',
        'payment_method',
        'status',
        'proof_file',
        'validated_by',
    ];

    public function paymentTransaction()
    {
        return $this->belongsTo(PaymentTransaction::class);
    }

    public function validateBy()
    {
        return $this->belongsTo(User::class, 'validated_by', 'id');
    }
}
