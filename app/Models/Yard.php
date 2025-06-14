<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yard extends Model
{
    use HasFactory;

    protected $table = 'yards'; // Tên bảng trong cơ sở dữ liệu
    protected $primaryKey = 'yard_id'; // Khóa chính
    public $timestamps = false; // Không sử dụng timestamps (created_at, updated_at)

    protected $fillable = [
        'yard_id',
        'type_id',
        'name',     // Tên sân
        'status'    // Trạng thái sân: 0 = hiển thị, 1 = ẩn
    ];

    // Quan hệ: Một sân có nhiều ảnh
    public function images()
    {
        return $this->hasMany(Image::class, 'yard_id', 'yard_id');
    }

    // Quan hệ: Một sân có nhiều đơn đặt (qua bảng trung gian order_details, không trực tiếp)
    public function orders()
    {
        return $this->hasMany(Order::class, 'yard_id', 'yard_id');
    }

    // Quan hệ: Một sân có nhiều khung giờ
    public function times()
    {
        return $this->hasMany(Time::class, 'yard_id', 'yard_id');
    }

    // Quan hệ: Một sân thuộc một loại sân
    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'type_id');
    }
}
