<?php

namespace App\Models;

use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    public function antrians()
    {
        return $this->hasMany(Antrian::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class);
    }

    public function documentation()
    {
        return $this->hasMany(Documentation::class);
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class);
    }
}
