<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Yard;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Time;
use App\Models\Type;
use App\Http\Requests\Client\PickRequest;

class PickController extends Controller
{
    public function index($yard_id, Request $request)
    {
        $yard = Yard::with('images')->find($yard_id);
        if (!$yard) {
            return redirect()->back()->with('error', 'Sân không tồn tại');
        }

        $selected_date = $request->query('date', date('Y-m-d'));

        // 1. Lấy tất cả khung giờ sân theo ngày và sắp xếp theo thứ tự tăng dần
        $times = Time::where('yard_id', $yard_id)
            ->where('date', $selected_date)
            ->orderBy('time', 'asc') // ✅ Sắp xếp theo thứ tự thời gian
            ->get();

        // 2. Lấy các khung giờ đã được admin xác nhận (status = 1)
        $adminBookedTimes = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->where('order_details.yard_id', $yard_id)
            ->where('order_details.date', $selected_date)
            ->where('orders.status', 1)
            ->pluck('order_details.time')
            ->toArray();

        // 3. Lấy các khung giờ đã chọn trong session của user hiện tại
        $sessionBookedTimes = [];
        $orders = session('orders', []);
        $userId = auth()->id();

        foreach ($orders as $order) {
            if (($order['user_id'] ?? null) == $userId
                && $order['yard_id'] == $yard_id
                && $order['date'] == $selected_date) {
                $sessionBookedTimes = array_merge($sessionBookedTimes, $order['times']);
            }
        }
        $sessionBookedTimes = array_unique($sessionBookedTimes);

        $yard_name = $yard->name;
        $yard_image = $yard->images->first();
        $user = auth()->check() ? auth()->user() : null;
        $types = Type::all();

        return view('client.pick', compact(
            'yard',
            'yard_name',
            'yard_image',
            'times',
            'adminBookedTimes',
            'sessionBookedTimes',
            'selected_date',
            'userId',
            'yard_id',
            'user',
            'types'
        ));
    }
    
    public function store(PickRequest $request)
    {
        $yard = Yard::findOrFail($request->input('yard_id'));

        $selected_times = $request->input('selected_times', []);
        if (empty($selected_times)) {
            return redirect()->back()->withErrors(['selected_times' => 'Vui lòng chọn ít nhất một khung giờ.']);
        }

        $total_price = (int) $request->input('total_price');

        // Lấy mảng giá từng slot từ input, dạng JSON string -> decode thành mảng số nguyên
        $price_per_slot = $request->input('price_per_slot', '[]');
        $price_per_slot_array = json_decode($price_per_slot, true);
        if (!is_array($price_per_slot_array) || count($price_per_slot_array) !== count($selected_times)) {
            return redirect()->back()->withErrors(['price_per_slot' => 'Dữ liệu giá khung giờ không hợp lệ.']);
        }

        $user = auth()->user();
        $created_at = now()->toDateTimeString();

        $order = [
            'user_id' => $user->user_id,
            'yard_id' => $yard->yard_id,
            'yard_name' => $yard->name,
            'name' => $user->fullname,
            'phone' => $user->phonenb,
            'times' => $selected_times,
            'date' => $request->input('date'),
            'price' => $total_price,
            'price_per_slot' => $price_per_slot_array, // lưu mảng giá từng slot
            'notes' => $request->input('notes'),
            'created_at' => $created_at,
        ];

        $orders = session('orders', []);
        $orders[] = $order;
        session(['orders' => $orders]);

        return redirect()->route('xac-nhan-dat-san');
    }

}
