<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'Invoices';
    public function History(){
        return $this->belongsTo(History::class,'history_id');
    }

    Public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

}
