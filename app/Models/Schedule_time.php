<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule_time extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'schedule_time';
    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
    ];
}
