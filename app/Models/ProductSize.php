<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    protected $table = 'product_sizes';
    protected $primaryKey = 'product_size_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'product_id',
        'quantity',
        'price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }

    // Thêm quan hệ ngược: size có nhiều chi tiết đơn hàng
    public function orderDetails()
    {
        return $this->hasMany(ProductOrderDetail::class, 'product_size_id', 'product_size_id');
    }
}
