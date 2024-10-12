<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Thay đổi đây
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable 
{
    use HasFactory, Notifiable;

    // Xác định tên bảng trong cơ sở dữ liệu
    protected $table = 'user';

    // user_id là khóa chính và tự động tăng
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    // Các thuộc tính có thể gán đại trà
    protected $fillable = [
        'user_id',
        'username',
        'password',
        'role',
        'fullname',
        'gender',
        'birthdate',
        'phonenb',
        'email'
    ];

}
