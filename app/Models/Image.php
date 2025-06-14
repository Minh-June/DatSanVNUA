<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images'; // Tên bảng
    protected $primaryKey = 'image_id'; // Khóa chính
    public $timestamps = false; // Không sử dụng timestamps

    protected $fillable = [
        'image_id',
        'yard_id', 
        'image' // Ảnh sân
    ]; // Các cột có thể gán

    // Quan hệ: Một ảnh thuộc về một sân
    public function yard()
    {
        return $this->belongsTo(Yard::class, 'yard_id', 'yard_id');
    }

    // Trả về đường dẫn ảnh
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image); // Lấy đường dẫn đầy đủ từ thư mục public
    }
}
