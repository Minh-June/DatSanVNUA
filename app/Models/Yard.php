<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yard extends Model
{
    // Khai báo tên bảng trong cơ sở dữ liệu
    protected $table = 'yard';

    // san_id là khóa chính và tự động tăng
    protected $primaryKey = 'san_id';
    public $timestamps = false;

    // Khai báo các cột có thể được gán giá trị
    protected $fillable = [
        'san_id', 
        'tensan', 
        'sosan'
    ];

    // Định nghĩa quan hệ với mô hình Image
    public function images()
    {
        return $this->hasMany(ImageYard::class, 'san_id', 'san_id');
    }
}
