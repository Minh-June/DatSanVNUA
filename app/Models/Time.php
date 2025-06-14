<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;

    protected $table = 'times'; // Tên bảng
    protected $primaryKey = 'time_id'; // Khóa chính
    public $timestamps = false; // Không sử dụng timestamps

    protected $fillable = [
        'time_id',
        'yard_id',
        'date',  // Ngày của khung giờ và giá sân theo yard_id
        'time',  // Khung giờ của sân
        'price', // Giá của khung giờ
    ];

    // Quan hệ: Một khung giờ thuộc về một sân
    public function yard()
    {
        return $this->belongsTo(Yard::class, 'yard_id', 'yard_id');
    }
}
