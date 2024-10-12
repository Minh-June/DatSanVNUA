<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ImageYard;
use App\Models\Order;
use App\Models\Timeslot;
use App\Models\Yard;

class PickController extends Controller
{
    public function index($id, Request $request)
    {
        // Lấy user_id từ Auth
        $userId = Auth::id();

        // Lấy thông tin sân dựa trên san_id
        $san = Yard::find($id);

        // Lấy hình ảnh của sân, nếu có
        $image = ImageYard::where('san_id', $id)->first();

        // Lấy thời gian tương ứng từ tbl_time_slots
        $time_slots = Timeslot::where('san_id', $san->san_id)->get();

        // Lấy ngày từ request hoặc mặc định là hôm nay
        $date = $request->input('date', date('Y-m-d'));

        // Lấy khung giờ đã đặt cho ngày cụ thể
        $booked_times = $this->getBookedTimes($san->san_id, $date);

        // Trả về view với thông tin sân
        return view('client.pick', [
            'tensan' => $san->tensan,
            'sosan' => $san->sosan,
            'userId' => $userId,
            'sanId' => $san->san_id,
            'time_slots' => $time_slots,
            'booked_times' => $booked_times,
            'image' => $image
        ]);
    }

    protected function getBookedTimes($sanId, $date)
    {
        $orders = Order::where('san_id', $sanId)
            ->where('date', $date) // Lọc theo ngày
            ->get();
        
        $bookedTimes = [];

        foreach ($orders as $order) {
            $times = explode(',', $order->time);
            $bookedTimes = array_merge($bookedTimes, $times);
        }

        return array_unique($bookedTimes);
    }

    public function getBookedTimesForDate(Request $request)
    {
        $date = $request->input('date');
        $sanId = $request->input('san_id');

        $bookedTimes = $this->getBookedTimes($sanId, $date);

        return response()->json(['booked_times' => $bookedTimes]);
    }

}