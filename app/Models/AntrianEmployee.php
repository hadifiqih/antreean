<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AntrianEmployee extends Pivot
{
    protected $table = null; // No physical table needed since we're using comma-separated values

    public function antrian()
    {
        return $this->belongsTo(Antrian::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}