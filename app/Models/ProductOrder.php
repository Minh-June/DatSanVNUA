<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
    use HasFactory;

    protected $table = 'product_orders'; // Tên bảng
    protected $primaryKey = 'product_order_id'; // Khóa chính
    public $timestamps = false; // Không sử dụng timestamps

    protected $fillable = [
        'product_order_id',
        'user_id',     // Người đặt hàng
        'store_id',    // Cửa hàng bán
        'total_price', // Tổng tiền đơn hàng
        'status',      // 0 = chờ xác nhận, 1 = đã xác nhận, 2 = hủy, 3 = đặt cọc
        'image',     // Ảnh thanh toán
        'address',     // Địa chỉ người mua
        'name',     // Tên người mua
        'phonenb',     // SĐT người mua
        'email',     // Email người mua
        'date',     // Ngày mau sản phẩm
        'notes',
    ];

    // Quan hệ: đơn hàng thuộc về người mua
    public function buyer()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Quan hệ: đơn hàng thuộc về cửa hàng
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    // Quan hệ: đơn hàng có nhiều chi tiết đơn hàng
    public function orderDetails()
    {
        return $this->hasMany(ProductOrderDetail::class, 'product_order_id', 'product_order_id');
    }
}
