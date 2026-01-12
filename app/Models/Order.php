<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    const STATUS_PENDING   = 0; // chờ xác nhận
    const STATUS_CONFIRMED = 1; // tự xác nhận
    const STATUS_CANCELLED = 2; // đã hủy
    const STATUS_DEPOSIT   = 3; // đã đặt cọc

    protected $fillable = [
        'user_id', // của chủ sân
        'date', // Ngày tạo đơn
        'name', // Tên khách đặt
        'phone',    // Số điện thoại khách đặt
        'image',    // Ảnh thanh toán nếu có
        'status',        // 0 = chờ, 1 = tự xác nhận, 2 = hủy, 3 = đặt cọc
        'auto_confirm',  // 1 = toàn kinh điển → auto xác nhận
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }
}
