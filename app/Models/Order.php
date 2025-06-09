<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders'; // Tên bảng
    protected $primaryKey = 'order_id'; // Khóa chính
    public $timestamps = false; // Không sử dụng timestamps

    // Các trạng thái đơn hàng
    const STATUS_PENDING   = 'chờ xác nhận';
    const STATUS_CONFIRMED = 'đã xác nhận';
    const STATUS_CANCELLED = 'đã hủy';

    protected $casts = [
        'status' => 'integer',
    ];

    protected $fillable = [
        'date', // Ngày tạo đơn, kiểu dữ liệu datetime
        'user_id',
        'name', // Tên người đặt
        'phone',   // Số điện thoại người đặt
        'image', // Ảnh thanh toán người đặt up lên
        'status', // Trạng thái đơn (mặc định là 0: chờ xác nhận, 1: đã xác nhận, 2: đã hủy)
    ]; // Các cột có thể gán

    // Quan hệ: Một đơn hàng thuộc về một người dùng
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Quan hệ: Một đơn hàng có nhiều chi tiết đơn hàng
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }
}
