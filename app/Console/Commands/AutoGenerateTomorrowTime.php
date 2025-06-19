<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Time;
use App\Models\Yard;

class AutoGenerateTomorrowTime extends Command
{
    protected $signature = 'app:auto-generate-today-time';
    protected $description = 'Tự động tạo khung giờ và giá cho hôm nay dựa trên khung giờ hôm qua';

    public function handle()
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        $total = 0;
        foreach (Yard::all() as $yard) {
            $total += Time::cloneFromDateToDate($yard->yard_id, $yesterday, $today);
        }

        $this->info("Đã tạo $total khung giờ cho hôm nay: $today.");
    }
}
