<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityType extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function dailyActivities()
    {
        return $this->hasMany(DailyActivity::class);
    }
}
