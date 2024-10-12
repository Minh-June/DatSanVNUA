<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageYard extends Model
{
    // Khai báo tên bảng trong cơ sở dữ liệu
    protected $table = 'image_yard';

    // image_id là khóa chính và tự động tăng
    protected $primaryKey = 'image_id';
    public $timestamps = false;

    // Khai báo các cột có thể được gán giá trị
    protected $fillable = [
        'image_id', 
        'san_id', 
        'image'
    ];

    // Định nghĩa quan hệ với mô hình San
    public function san()
    {
        return $this->belongsTo(Yard::class, 'san_id', 'san_id');
    }

    public function images()
    {
        return $this->hasMany(ImageYard::class, 'order_id'); // Thay đổi 'order_id' thành khóa ngoại của bạn
    }

    // Phương thức để lấy URL của ảnh
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }
}
