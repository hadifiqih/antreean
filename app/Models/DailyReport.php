<?php

namespace App\Models;

use App\Models\AdsReport;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id',
        'user_id',
        'kendala',
        'agendas'
    ];

    protected $casts = [
        'agendas' => 'array'
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activities()
    {
        return $this->hasMany(DailyActivity::class);
    }

    public function offers()
    {
        return $this->hasMany(DailyOffer::class);
    }

    public function adsReports()
    {
        return $this->hasMany(AdsReport::class);
    }
}