<?php

namespace App\Models;

use App\Models\Platform;
use Illuminate\Database\Eloquent\Model;

class AdsReport extends Model
{
    protected $fillable = [
        'daily_report_id',
        'ads_id',
        'platform_id',
        'job_name',
        'lead_amount',
        'total_omset',
        'analisa',
        'kendala'
    ];

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class, 'daily_report_id', 'id');
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class, 'platform_id', 'id');
    }
}
