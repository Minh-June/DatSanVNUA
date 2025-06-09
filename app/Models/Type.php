<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'types'; // Tên bảng
    protected $primaryKey = 'type_id'; // Khóa chính
    public $timestamps = false; // Không sử dụng timestamps

    protected $fillable = [
        'type_id',
        'name' // Tên loại sân
    ]; // Các cột có thể gán

    // Quan hệ: Một loại sân có nhiều sân
    public function yards()
    {
        return $this->hasMany(Yard::class, 'type_id', 'type_id'); // Sửa lại tham số khóa quan hệ
    }
}
