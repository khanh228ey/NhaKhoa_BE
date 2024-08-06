<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
    ];
    protected $fillable = ['name', 'date', 'time', 'phone'];

    public function Services(){
        return $this->belongsToMany(Service::class,'appointment_detail','appointment_id','service_id');
    }
    public function Doctor(){
        return $this->belongsTo(User::class,'doctor_id');
    }
   
}