<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyOffer extends Model
{
    use HasFactory;

    protected $fillable = ['daily_report_id', 'offer_id', 'is_prospect', 'updates'];

    protected $casts = [
        'updates' => 'array'
    ];

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
