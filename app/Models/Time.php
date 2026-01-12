<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Time extends Model
{
    use HasFactory;

    protected $table = 'times';
    protected $primaryKey = 'time_id';
    public $timestamps = false;

    protected $fillable = [
        'yard_id',
        'start',          // giờ bắt đầu
        'end',            // giờ kết thúc
        'price_weekday',  // giá T2-T6, NULL nếu không cho thuê
        'price_weekend',  // giá T7-CN, NULL nếu không cho thuê
        'is_classic',     // 0 = cung giờ kinh điển, 1 = không
        'status',     // 0 = hiển thị, 1 = ẩn
    ];

    public function yard()
    {
        return $this->belongsTo(Yard::class, 'yard_id', 'yard_id');
    }

    // Lấy giá dựa theo ngày
    public function getPriceByDate($date)
    {
        $dayOfWeek = Carbon::parse($date)->dayOfWeek; // 0=CN, 1=Th2,...,6=Th7
        return ($dayOfWeek >= 1 && $dayOfWeek <= 5) ? $this->price_weekday : $this->price_weekend;
    }
}
