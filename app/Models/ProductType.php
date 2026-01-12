<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $table = 'product_types'; // Tên bảng
    protected $primaryKey = 'product_type_id'; // Khóa chính
    public $timestamps = false; // Không sử dụng timestamps

    protected $fillable = [
        'store_id',
        'name', // Tên loại sản phẩm
    ];

    // Một loại có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'product_type_id', 'product_type_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'store_id');
    }
}
