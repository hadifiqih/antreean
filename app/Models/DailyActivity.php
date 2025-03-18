<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyActivity extends Model
{
    use HasFactory;

    protected $fillable = ['daily_report_id', 'activity_type_id', 'description', 'amount'];

    public function dailyReport()
    {
        return $this->belongsTo(DailyReport::class);
    }

    public function activityType()
    {
        return $this->belongsTo(ActivityType::class);
    }
}