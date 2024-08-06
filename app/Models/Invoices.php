<?php

namespace App\Models;

use App\Events\InvoiceCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'invoices';
    protected $fillable = ['method_payment','status'];
    protected $casts = [
        'id' => 'integer',
        'history_id' => 'integer',
        'method_payment' => 'integer',
        'status' => 'integer',
    ];
    public function History(){
        return $this->belongsTo(History::class,'history_id');
    }

    Public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
