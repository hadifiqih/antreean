<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $table = 'machines';
    
    protected $fillable = [
        'machine_code',
        'name',
        'type',
        'description',
        'is_active'
    ];

    public function antrian()
    {
        return $this->hasMany(Antrian::class, 'machine_code', 'machine_code');
    }
}
