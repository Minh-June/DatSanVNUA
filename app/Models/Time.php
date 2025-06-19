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

    // Hàm tĩnh sao chép khung giờ hôm qua sang ngày hôm nay nếu chưa có
    public static function cloneFromDateToDate($yard_id, $fromDate, $toDate): int
    {
        $source = self::where('yard_id', $yard_id)
            ->whereDate('date', $fromDate)
            ->orderBy('time')
            ->get();

        $count = 0;
        foreach ($source as $time) {
            $exists = self::where('yard_id', $yard_id)
                ->where('date', $toDate)
                ->where('time', $time->time)
                ->exists();

            if (!$exists) {
                self::create([
                    'yard_id' => $yard_id,
                    'time' => $time->time,
                    'price' => $time->price,
                    'date' => $toDate,
                ]);
                $count++;
            }
        }

        return $count;
    }
}
