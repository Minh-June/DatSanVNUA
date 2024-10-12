<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    // Khai báo tên bảng trong cơ sở dữ liệu
    protected $table = 'time_slot';

    // time_slot_id là khóa chính và tự động tăng
    protected $primaryKey = 'time_slot_id';
    public $timestamps = false;

    // Khai báo các cột có thể được gán giá trị
    protected $fillable = [
        'time_slot_id', 
        'san_id', 
        'time_slot',
        'price'
    ];

    // Mối quan hệ với mô hình San
    public function san()
    {
        return $this->belongsTo(Yard::class, 'san_id', 'san_id');
    }
}
