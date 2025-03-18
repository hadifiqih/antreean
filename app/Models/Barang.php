<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'barangs';

    protected $fillable = [
        'ticket_order',
        'data_kerja_id',
        'customer_id',
        'kategori_id',
        'job_id',
        'sales_id',
        'price',
        'qty',
        'note',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
}
