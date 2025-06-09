<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // Tên bảng
    protected $primaryKey = 'user_id'; // Khóa chính
    public $timestamps = false; // Không sử dụng timestamps

    protected $fillable = [
        'user_id',
        'username', // Tên đăng nhập
        'password', // Mật khẩu
        'role', // Quyền hạn (0: admin, 1: user)
        'fullname', // Họ tên
        'gender', // Giới tính (0: nam, 1: nữ, 2: khác)
        'birthdate', // Ngày sinh
        'phonenb', // Số điện thoại
        'email' 
    ]; // Các cột có thể gán

    // Quan hệ: Một người dùng có thể có nhiều đơn đặt sân
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }
}
