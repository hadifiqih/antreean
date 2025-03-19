<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Antrian;
use App\Models\Design;
use App\Models\Order;
use App\Models\User;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'nip',
        'name',
        'where_born',
        'date_of_birth',
        'jenis_kelamin',
        'email',
        'phone',
        'address',
        'role',
        'division',
        'office',
        'photo',
        'joining_date',
        'bank_name',
        'bank_account',
        'status',
        'user_id',
        'remember_token',
    ];

    //relasi dengan tabel antrian
    public function operatedAntrians()
    {
        return $this->belongsToMany(Antrian::class, null, 'id', 'operator_id')
            ->using(fn($query) => $query->whereRaw("FIND_IN_SET(?, operator_id)", [$this->id]));
    }

    public function finishedAntrians()
    {
        return $this->belongsToMany(Antrian::class, null, 'id', 'finisher_id')
            ->using(fn($query) => $query->whereRaw("FIND_IN_SET(?, finisher_id)", [$this->id]));
    }

    public function qualityCheckedAntrians()
    {
        return $this->belongsToMany(Antrian::class, null, 'id', 'qc_id')
            ->using(fn($query) => $query->whereRaw("FIND_IN_SET(?, qc_id)", [$this->id]));
    }

    // Keep old relationships for backward compatibility
    public function antrianAsOperator()
    {
        return $this->hasMany(Antrian::class, 'operator_id');
    }

    public function antrianAsFinisher()
    {
        return $this->hasMany(Antrian::class, 'finisher_id');
    }

    public function antrianAsQuality()
    {
        return $this->hasMany(Antrian::class, 'qc_id');
    }

    public function design(){
        return $this->hasMany(Design::class);
    }

    public function jobs(){
        return $this->belongsToMany(Job::class);
    }

    //relasi dengan tabel user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function isOperator()
    {
        return $this->role === 'operator';
    }

    public function isFinisher()
    {
        return $this->role === 'finisher';
    }

    public function isQuality()
    {
        return $this->role === 'quality';
    }

    public function isDesign()
    {
        return $this->role === 'design';
    }

    public function isSales()
    {
        return $this->role === 'sales';
    }

}
