<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'status', 'description', 'image','updated_at'];
    protected $casts = [
        'id' => 'integer',
        'status' => 'integer',
    ];
    public function Services(){
        return $this->hasMany(Service::class,'category_id');
    }
    
    Public function translation(){
        return $this->hasOne(CategoryTranslation::class,'category_id');
    }
}

