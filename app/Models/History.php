<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Claims\Custom;

class History extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
    ];
    public function Customer(){
        return $this->belongsTo(Customer::class,'customer_id');
    }
    
    public function Doctor(){
        return $this->belongsTo(User::class,'doctor_id');
    }

    public function Services(){
        return $this->belongsToMany(Service::class,'history_detail','history_id','service_id')->withPivot('quantity','price');
    }

    Public function invoice(){
        return $this->hasOne(Invoices::class,'history_id');
    }


    public function historyDetails()
    {
        return $this->hasMany(History_detail::class);
    }

}
