<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['sales_id', 'job_id', 'price', 'qty', 'total', 'platform_id', 'updates', 'description', 'is_closing', 'is_prospect'];

    protected $casts = [
        'updates' => 'array'
    ];

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function dailyOffers()
    {
        return $this->hasMany(DailyOffer::class);
    }
}