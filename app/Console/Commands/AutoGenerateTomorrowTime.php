<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Time;
use App\Models\Yard;

class AutoGenerateTomorrowTime extends Command
{
    protected $signature = 'app:auto-generate-today-time';
    protected $description = 'Tự động tạo khung giờ và giá cho ngày mai dựa trên hôm nay';

    public function handle()
    {
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $today = date('Y-m-d');

        $total = 0;
        foreach (Yard::all() as $yard) {
            $total += Time::cloneFromDateToDate($yard->yard_id, $today, $tomorrow);
        }

        $this->info("Đã tạo $total khung giờ cho ngày mai: $tomorrow.");
    }
}
