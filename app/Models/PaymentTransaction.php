<?php

namespace App\Models;

use App\Models\Antrian;
use App\Models\Installment;
use App\Models\AdditionalCost;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'antrian_id',
        'total_amount',
        'payment_status',
    ];

    public function antrian()
    {
        return $this->belongsTo(Antrian::class);
    }


    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function additionalCosts()
    {
        return $this->hasMany(AdditionalCost::class);
    }

    public function calculateRemainingAmount()
    {
        $totalPaid = $this->installments()->sum('amount');
        return max(0, $this->total_amount - $totalPaid);
    }

    public function updateStatusPayment()
    {
        $remainingAmount = $this->calculateRemainingAmount();

        if ($remainingAmount == 0) {
            $this->update(['payment_status' => 'paid']);
        } elseif ($remainingAmount < $this->total_amount) {
            $this->update(['payment_status' => 'partially_paid']);
        } else {
            $this->update(['payment_status' => 'unpaid']);
        }
    }
}