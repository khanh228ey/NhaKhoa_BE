<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'notifications';
    protected $fillable = ['title', 'status', 'message','created_at','appointment_id'];
    protected $casts = [
        'id' => 'integer',
        'appointment_id' => 'integer',
    ];

    Public function appointment(){
        return $this->hasOne(Appointment::class,'id','appointment_id');
    }
}
