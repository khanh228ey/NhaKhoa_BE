<?php

namespace App\Models;

use App\Events\InvoiceCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'Invoices';
    protected $fillable = ['method_payment','status'];
    public function History(){
        return $this->belongsTo(History::class,'history_id');
    }

    Public function user(){
        return $this->belongsTo(User::class,'user_id');
    }


    protected $dispatchesEvents = [
        'created' => InvoiceCreated::class,
    ];
}
