<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    public function Appointment_detail(){
        return $this->belongsToMany(Service::class,'appointment_detail','appointment_id','service_id');
    }
}
