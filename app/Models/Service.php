<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'status', 'min_price', 'max_price', 'image','unit', 'category_id','description','quantity_sold','updated_at'];
    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function histories()
    {
        return $this->belongsToMany(History::class, 'history_detail', 'service_id', 'history_id')->withPivot('quantity','price');
    }

    public function appointment()
    {
        return $this->belongsToMany(Appointment::class, 'Appointment_detail', 'service_id', 'appointment_id');
    }

    Public function translation(){
        return $this->hasOne(ServiceTranslation::class,'service_id');
    }
}
