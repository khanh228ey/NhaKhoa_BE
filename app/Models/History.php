<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Claims\Custom;

class History extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    public function Customer(){
        return $this->belongsTo(Customer::class,'customer_id')->select('id','name');
    }
    
    public function Doctor(){
        return $this->belongsTo(User::class,'doctor_id')->select('id','name');
    }
}
