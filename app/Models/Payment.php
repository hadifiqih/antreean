<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Antrian;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_order', 'total_payment', 'payment_amount', 'shipping_cost', 'installation_cost', 'remaining_payment' ,'payment_method', 'payment_status', 'payment_proof', 'is_validated', 'validated_by', 'validated_at'];

    public function antrian()
    {
        return $this->belongsTo(Antrian::class, 'ticket_order', 'ticket_order');
    }
}
