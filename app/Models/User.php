<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    Use HasRoles;
    protected $primaryKey = 'id'; // Đặt khóa chính là cột id
    public $incrementing = false; // Khóa chính không tự tăng
    protected $keyType = 'string'; // Khóa chính là kiểu chuỗi
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'phone_number',
        'avatar',
        'gender',
        'birthday',
        'address',
        'description',
        'role_id',
        'status',
        'updated_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'integer',
        'gender' => 'integer',
        'role_id' => 'integer'
    ];
    protected static function boot()
    {
        parent::boot();

        // Sự kiện tạo model
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $prefix = 'DH';
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


    //Thiet lap quan he 
    public function role()
    {
        return $this->belongsTo(Role::class)->select('id','name');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    Public function schedule(){
        return $this->hasMany(Schedule::class,'doctor_id');
    }
    Public function translation(){
        return $this->hasOne(DoctorTranslation::class,'doctor_id');
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [
            'name' => $this->name,
            'avatar' => $this->avatar,
            'role' => $this->role->name,
        ];
    }
}

