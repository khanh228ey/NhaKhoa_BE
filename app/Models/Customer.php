<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $primaryKey = 'id'; // Đặt khóa chính là cột id
    public $incrementing = false; // Khóa chính không tự tăng
    protected $keyType = 'string'; // Khóa chính là kiểu chuỗi
    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();


        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $prefix = 'BN';
                $maxId = self::where('id', 'LIKE', "{$prefix}%")->max('id');
                $nextId = $model->generateNextId($prefix, $maxId);
                $model->{$model->getKeyName()} = $nextId;
            }
        });
    }

    private function generateNextId($prefix, $maxId)
    {
        if ($maxId) {
            $number = intval(substr($maxId, 2)) + 1; 
            return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT); 
        }
        return $prefix . '00001'; 
    }
}
