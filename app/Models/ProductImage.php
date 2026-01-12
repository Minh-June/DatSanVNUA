<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $table = 'product_images'; // Tên bảng
    protected $primaryKey = 'product_image_id'; // Khóa chính
    public $timestamps = false; // Không dùng timestamps

    protected $fillable = [
        'product_id',
        'image', // tên file hoặc đường dẫn ảnh
    ];

    // Ảnh thuộc về một sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}
