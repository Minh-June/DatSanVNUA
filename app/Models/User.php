<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // TĂªn báº£ng
    protected $primaryKey = 'user_id'; // KhĂ³a chĂ­nh
    public $timestamps = false; // KhĂ´ng sá»­ dá»¥ng timestamps

    protected $fillable = [
        'user_id',
        'username', // TĂªn Ä‘Äƒng nháº­p
        'password', // Máº­t kháº©u
        'role', // Quyá»n háº¡n (0: admin, 1: user)
        'fullname', // Há» tĂªn
        'gender', // Giá»›i tĂ­nh (0: nam, 1: ná»¯, 2: khĂ¡c)
        'birthdate', // NgĂ y sinh
        'phonenb', // Sá»‘ Ä‘iá»‡n thoáº¡i
        'email' 
    ]; // CĂ¡c cá»™t cĂ³ thá»ƒ gĂ¡n

    // Quan há»‡: Má»™t ngÆ°á»i dĂ¹ng cĂ³ thá»ƒ cĂ³ nhiá»u Ä‘Æ¡n Ä‘áº·t sĂ¢n
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }
}
