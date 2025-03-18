<?php

namespace App\Models;

use App\Models\Barang;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AntrianIklan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'antrian_iklans';

    protected $fillable = [
        'platform_id',
        'sales_id',
        'job_id',
        'barang_id',
        'is_iklan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }
}
