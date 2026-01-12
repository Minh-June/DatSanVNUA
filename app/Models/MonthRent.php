<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthRent extends Model
{
    use HasFactory;

    protected $table = 'month_rents';
    protected $primaryKey = 'month_rent_id';
    public $timestamps = false;

    protected $fillable = [
        'yard_id',     // sân thuê
        'user_id',     // khách thuê
        'weekday',     // ngày trong tuần (0-6)
        'start',       // giờ bắt đầu
        'end',         // giờ kết thúc
        'from_date',   // bắt đầu thuê
        'to_date',     // kết thúc thuê
        'price',       // giá thuê
        'status',       // 0 = chờ xác nhận, 1 = xác nhận, 2 = hủy, =3 đặt cọc
        'date',       // Ngày tạo đơn
    ];

    public function yard()
    {
        return $this->belongsTo(Yard::class, 'yard_id', 'yard_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
