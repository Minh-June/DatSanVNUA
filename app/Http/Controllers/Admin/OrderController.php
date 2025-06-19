<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Yard;
use App\Models\Time;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->get('selected_date');
        $yardName = $request->get('yard_name');
        $status = $request->get('status'); // lấy trạng thái

        $orders = Order::with(['orderDetails.yard']);

        if ($selectedDate) {
            $orders->whereDate('date', $selectedDate);
        }

        // Lọc theo tên sân nếu có
        if ($yardName) {
            $orders->whereHas('orderDetails.yard', function ($query) use ($yardName) {
                $query->where('name', $yardName);
            });
        }

        // Lọc theo trạng thái nếu được truyền
        if (!is_null($status)) {
            $orders->where('status', $status);
        }

        // Sắp xếp theo trạng thái và ngày
        $orders = $orders
            ->orderByRaw("FIELD(status, 0, 1, 2)")
            ->orderBy('date')
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function edit($order_id)
    {
        $order = Order::with('orderDetails.yard')->findOrFail($order_id);
        $totalPrice = $order->orderDetails->sum('price');
        return view('admin.orders.update', compact('order', 'totalPrice'));
    }

    public function updateStatus(Request $request, $order_id)
    {
        $request->validate([
            'status' => 'required|in:0,1,2',
        ]);

        $order = Order::findOrFail($order_id);
        $order->status = (int) $request->status;
        $order->save();

        return redirect()->route('quan-ly-don-dat-san')->with('success', 'Cập nhật trạng thái đơn đặt sân thành công !');
    }

    public function delete($order_id)
    {
        $order = Order::find($order_id);
        if ($order) {
            $order->delete();
            return redirect()->route('quan-ly-don-dat-san')->with('success', 'Xóa đơn đơn đặt sân thành công.');
        }
        return redirect()->route('quan-ly-don-dat-san')->with('error', 'Không tìm thấy đơn đơn đặt sân.');
    }
}
