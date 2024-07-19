<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History_detail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'history_detail';

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function history()
    {
        return $this->belongsTo(History::class);
    }
}
