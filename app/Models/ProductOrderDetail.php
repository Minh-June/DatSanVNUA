<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrderDetail extends Model
{
    use HasFactory;

    protected $table = 'product_order_details';
    protected $primaryKey = 'product_order_detail_id';
    public $timestamps = false;

    protected $fillable = [
        'product_order_detail_id',
        'product_order_id',
        'product_id', // Sản phẩm
        'quantity', // Số lượng
        'price',    // Đơn giá
        'product_size_id', // Size sản phẩm nếu có
    ];

    // Quan hệ: chi tiết đơn hàng thuộc về đơn hàng
    public function order()
    {
        return $this->belongsTo(ProductOrder::class, 'product_order_id', 'product_order_id');
    }

    // Quan hệ: chi tiết đơn hàng thuộc về sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // Quan hệ: chi tiết đơn hàng thuộc về size (nếu có)
    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'product_size_id', 'product_size_id');
    }
}
