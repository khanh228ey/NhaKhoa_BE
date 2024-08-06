<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'doctor_translation';
    protected $casts = [
        'id' => 'integer',
    ];

}
