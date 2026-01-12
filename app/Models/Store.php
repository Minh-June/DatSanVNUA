<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $table = 'stores';
    protected $primaryKey = 'store_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id', // Chủ sở hữu cửa hàng
        'name',    // Tên cửa hàng
        'status',  // 0 = hoạt động, 1 = ẩn
    ];

    // Một cửa hàng thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Một cửa hàng có nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'store_id', 'store_id');
    }
}

