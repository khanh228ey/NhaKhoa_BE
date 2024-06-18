<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $table = 'schedule';
    public $timestamps = false;
    public function time()
    {
        return $this->belongsTo(Schedule_time::class,'time_id');
    }

    public function Doctor()
    {
        return $this->belongsTo(User::class,'doctor_id')->select('id','name');
    }

}
