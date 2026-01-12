<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    protected $fillable = [
        'store_id',
        'product_type_id',
        'name',
        'description',
        'price',
        'status',
        'quantity',
        'product_size_id', //1 là có size, bỏ trống là không có size, cột này không phải khóa ngoại 
    ];

    // Một sản phẩm thuộc về cửa hàng
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }

    // Một sản phẩm thuộc về loại sản phẩm
    public function type()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id', 'product_type_id');
    }

    // Một sản phẩm có nhiều ảnh
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    // Một sản phẩm có nhiều size
    public function sizes()
    {
        return $this->hasMany(ProductSize::class, 'product_id', 'product_id');
    }
}