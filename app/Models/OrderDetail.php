<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_details'; // Tên bảng 
    protected $primaryKey = 'order_detail_id'; // Khóa chính
    public $timestamps = false; // Không sử dụng timestamps

    protected $fillable = [
        'order_id',
        'date', // Ngày đặt sân
        'time', // Khung giờ của sân
        'yard_id',
        'price', // Giá của khung giờ
        'notes', // Ghi chú của người dùng
    ]; // Các cột có thể gán

    // Quan hệ: Một chi tiết đơn hàng thuộc về một đơn hàng
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    // Quan hệ: Một chi tiết đơn hàng thuộc về một sân
    public function yard()
    {
        return $this->belongsTo(Yard::class, 'yard_id', 'yard_id');
    }
}
