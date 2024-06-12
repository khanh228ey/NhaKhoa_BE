<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id')->select(['id', 'name']);;
    }

}
