<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Yard;
use App\Models\Time;
use Illuminate\Support\Facades\DB;

class OrderDetailController extends Controller
{
    public function index(Request $request, $order_detail_id)
    {
        $editDetail = OrderDetail::with('yard', 'order')->findOrFail($order_detail_id);
        $order = Order::with('orderDetails.yard')->find($editDetail->order_id);
        $yards = Yard::all();

        $selectedYard = $request->input('yard_id', $editDetail->yard_id);
        $selectedDate = $request->input('date', $editDetail->date);

        $timesForSelectedDate = collect();

        if ($selectedYard && $selectedDate) {
            $bookedTimes = DB::table('order_details')
                ->join('orders', 'order_details.order_id', '=', 'orders.order_id')
                ->where('orders.status', 1)
                ->where('order_details.date', $selectedDate)
                ->where('order_details.yard_id', $selectedYard)
                ->where('order_details.order_detail_id', '!=', $order_detail_id) // exclude current detail
                ->pluck('order_details.time')
                ->toArray();

            $timesForSelectedDate = Time::where('yard_id', $selectedYard)
                ->whereDate('date', $selectedDate)
                ->whereNotIn('time', $bookedTimes)
                ->get();
        }

        $totalPrice = $order ? $order->orderDetails->sum('price') : 0;

        return view('admin.orders.update', compact('order', 'editDetail', 'timesForSelectedDate', 'yards', 'totalPrice'));
    }

    public function update(Request $request, $order_detail_id)
    {
        $request->validate([
            'yard_id' => 'required|exists:yards,yard_id',
            'date' => 'required|date',
            'time' => 'required|string',
            'price' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $orderDetail = OrderDetail::findOrFail($order_detail_id);

        // Tổng tiền cũ của đơn
        $order = $orderDetail->order;
        $oldTotal = $order->orderDetails->sum('price');

        // Cập nhật chi tiết đơn
        $orderDetail->update([
            'yard_id' => $request->yard_id,
            'date' => $request->date,
            'time' => $request->time,
            'price' => $request->price,
            'notes' => $request->notes,
        ]);

        // Tính lại tổng tiền mới
        $order->refresh();
        $newTotal = $order->orderDetails->sum('price');

        $diff = $newTotal - $oldTotal;

        if ($diff > 0) {
            $message = "Cập nhật chi tiết đơn thành công. Tổng tiền tăng thêm " . number_format($diff, 0, ',', '.') . " VND.";
        } elseif ($diff < 0) {
            $message = "Cập nhật chi tiết đơn thành công. Tổng tiền giảm " . number_format(abs($diff), 0, ',', '.') . " VND.";
        } else {
            $message = "Cập nhật chi tiết đơn thành công. Tổng tiền không thay đổi.";
        }

        return redirect()->route('cap-nhat-chi-tiet-don', $order_detail_id)
            ->with('price_change_message', $message);
}

    public function delete($order_detail_id)
    {
        $detail = OrderDetail::findOrFail($order_detail_id);
        $order_id = $detail->order_id;
        $detail->delete();

        return redirect()->route('cap-nhat-don-dat-san', $order_id)
            ->with('success', 'Đã xóa chi tiết đơn thành công.');
    }
}
