<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_details';
    protected $primaryKey = 'order_detail_id';
    public $timestamps = false;

    protected $fillable = [
        'order_id', // Để biết bố của đơn chi tiết là ai
        'date', // Ngày khách thuê
        'time', // Thời gian thuê
        'yard_id',  // Tên sân
        'type_id',  // Loại sân
        'price',    // Giá
        'notes',    // Ghi chú nếu có
        'is_classic', // 1 = kinh điển, 0 = gợi ý
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function yard()
    {
        return $this->belongsTo(Yard::class, 'yard_id', 'yard_id');
    }

    public function type()
    {
        return $this->belongsTo(Type::class, 'type_id', 'type_id');
    }
}
