<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'username',
        'password',
        'role',
        'fullname',
        'gender',
        'birthdate',
        'phonenb',
        'email',
        'manager_id',
        'acc_name',
        'acc_number',
        'acc_type',
        'qr_code',
        'image',
        'www',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    public function yards()
    {
        return $this->hasMany(Yard::class, 'user_id', 'user_id');
    }

    public function newsTypes()
    {
        return $this->hasMany(NewsType::class, 'user_id', 'user_id');
    }

    public function store()
    {
        return $this->hasOne(Store::class, 'user_id', 'user_id');
    }
}