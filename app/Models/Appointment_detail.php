<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment_detail extends Model
{

    protected $table = 'appointment_detail';
    use HasFactory;
    protected $casts = [
        'id' => 'integer',
        'service_id' => 'integer',
        'appointment_id' => 'integer',
    ];
    
}
