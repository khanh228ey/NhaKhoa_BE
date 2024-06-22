<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name', 'status', 'description', 'image'];
    public function Services(){
        return $this->hasMany(Service::class,'category_id');
    }
 
}
