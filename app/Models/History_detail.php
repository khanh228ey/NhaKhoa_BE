<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History_detail extends Model
{
    use HasFactory;

    
    public function History_detail(){
        return $this->belongsToMany(History::class,'History_detail','history_id','service_id');
    }
}
