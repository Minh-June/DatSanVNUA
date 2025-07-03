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

    // Hàm sao chép khung giờ từ một ngày sang ngày khác cho một sân cụ thể
    public static function cloneFromDateToDate($yard_id, $fromDate, $toDate): int
    {
        // Lấy các khung giờ từ ngày nguồn
        $sourceTimes = self::where('yard_id', $yard_id)
            ->whereDate('date', $fromDate)
            ->orderBy('time')
            ->get();

        $count = 0;

        foreach ($sourceTimes as $time) {
            // Kiểm tra nếu khung giờ này đã tồn tại ở ngày đích thì bỏ qua
            $alreadyExists = self::where('yard_id', $yard_id)
                ->whereDate('date', $toDate)
                ->where('time', $time->time)
                ->exists();

            if (!$alreadyExists) {
                // Tạo khung giờ mới cho ngày đích
                self::create([
                    'yard_id' => $yard_id,
                    'date'    => $toDate,
                    'time'    => $time->time,
                    'price'   => $time->price,
                ]);
                $count++;
            }
        }

        return $count;
    }
}
