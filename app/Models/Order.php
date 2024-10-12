<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Xác định tên bảng trong cơ sở dữ liệu
    protected $table = 'order';

    // order_id là khóa chính và tự động tăng
    protected $primaryKey = 'order_id';

    // Tắt timestamps
    public $timestamps = false;

    // Các thuộc tính có thể gán đại trà
    protected $fillable = [
        'order_id',
        'name',
        'phone',
        'date',
        'time',
        'price',
        'notes',
        'status',
        'san_id',
        'user_id',
        'image'
    ];

    // Định nghĩa mối quan hệ với San
    public function san()
    {
        return $this->belongsTo(Yard::class, 'san_id'); // Đảm bảo rằng 'san_id' là tên khóa ngoại trong bảng tbl_order
    }

    // Định nghĩa mối quan hệ với Users
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id'); // Đảm bảo rằng 'user_id' là tên khóa ngoại trong bảng tbl_order
    }
}
