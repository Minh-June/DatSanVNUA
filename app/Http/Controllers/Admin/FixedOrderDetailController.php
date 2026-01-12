<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonthRent;
use App\Models\Type;
use App\Models\Yard;

class FixedOrderDetailController extends Controller
{
    // Trang chi tiết đơn cố định
    public function index(Request $request, $order_id)
    {
        $order = MonthRent::with('yard.type', 'user')->findOrFail($order_id);

        // Chỉ bật chế độ sửa khi có ?edit=1
        $editDetail = $request->has('edit') ? $order : null;

        $types = Type::all();

        $selectedType = $request->type_id ?? $order->yard->type_id;
        $selectedYard = $request->yard_id ?? $order->yard_id;
        $yard_id = $selectedYard;

        // Lọc sân theo loại và theo user hiện tại
        $userId = auth()->id(); // user đang đăng nhập
        $yards = Yard::where('type_id', $selectedType)
                     ->where('user_id', $userId)
                     ->get();

        // Ngày bắt đầu/kết thúc
        $from_date = $editDetail ? $editDetail->from_date : date('Y-m-d');
        $to_date = $editDetail ? $editDetail->to_date : date('Y-m-d', strtotime('+1 month'));

        // Ngày trong tuần (mảng số)
        $selectedWeekdays = $editDetail ? explode(',', $editDetail->weekday) : [];

        // Giờ từ/đến
        $time_from = $editDetail ? $editDetail->start : '06:00';
        $time_to = $editDetail ? $editDetail->end : '22:00';

        // Giá tiền
        $price = $editDetail ? $editDetail->price : 0;

        return view('admin.fixedorder.update', compact(
            'order','types','yards',
            'selectedType','selectedYard',
            'editDetail','yard_id','userId',
            'from_date','to_date','selectedWeekdays',
            'time_from','time_to','price'
        ));
    }

    // Cập nhật chi tiết đơn cố định
    public function update(Request $request, $month_rent_id)
    {
        $order = MonthRent::findOrFail($month_rent_id);

        $request->validate([
            'yard_id'     => 'required|exists:yards,yard_id',
            'price'       => 'required|numeric|min:0',
            'start'       => 'required|date_format:H:i',
            'end'         => 'required|date_format:H:i|after:start',
            'from_date'   => 'required|date',
            'to_date'     => 'required|date|after_or_equal:from_date',
            'weekdays'    => 'required|string', // ví dụ "0,1,2"
        ]);

        $order->yard_id   = $request->yard_id;
        $order->price     = $request->price;
        $order->start     = $request->start;
        $order->end       = $request->end;
        $order->from_date = $request->from_date;
        $order->to_date   = $request->to_date;
        $order->weekday   = $request->weekdays;

        $order->save();

        return redirect()->back()->with('success', 'Cập nhật chi tiết đơn thuê cố định thành công!');
    }

    // Ajax: lấy sân theo loại và user hiện tại
    public function getByType($type_id)
    {
        $userId = auth()->id();
        $yards = Yard::where('type_id', $type_id)
                     ->where('user_id', $userId)
                     ->get();

        return response()->json($yards);
    }
}
