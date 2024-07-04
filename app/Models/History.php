<?php

namespace App\Models;

use App\Repositories\InvoiceRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Claims\Custom;

class History extends Model
{
    use HasFactory;
    public $timestamps = false;
    
    public function Customer(){
        return $this->belongsTo(Customer::class,'customer_id');
    }
    
    public function Doctor(){
        return $this->belongsTo(User::class,'doctor_id');
    }

    public function Services(){
        return $this->belongsToMany(Service::class,'history_detail','history_id','service_id')->withPivot('quantity','price');
    }

    Public function invoice(){
        return $this->hasOne(Invoices::class,'history_id');
    }

    protected static function booted()
    {
        static::saved(function ($history) {
            // Kiểm tra nếu thuộc tính 'noted' đã được thiết lập và nếu không có invoice nào đã tồn tại
            if ($history->noted && !$history->invoice) {
                $invoiceRepository = new InvoiceRepository();
                $invoiceRepository->addInvoice(['history_id' => $history->id]);
            }
        });
    }

}
