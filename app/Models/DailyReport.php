<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DailyReport extends Model
{
    use HasFactory;

    protected $fillable = ['sales_id', 'user_id', 'omset'];

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
}