<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History_detail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'history_detail';
    protected $casts = [
        'id' => 'integer',
        'service_id' => 'integer',
        'history_id' => 'integer',
        'quantity' => 'integer',
        'price' => 'integer',
    ];
    public function service()
    {
        return $this->belongsTo(Service::class,'service_id');
    }

    public function history()
    {
        return $this->belongsTo(History::class,'history_id');
    }
}
