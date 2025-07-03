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
        $typeName = $request->get('type_name');
        $status = $request->get('status');

        $orders = Order::with(['orderDetails.yard.type']);

        // Lọc theo ngày
        if ($selectedDate) {
            $orders->whereDate('date', $selectedDate);
        }

        // Lọc theo tên sân
        if ($yardName && $yardName !== 'Sân không tồn tại') {
            $orders->whereHas('orderDetails.yard', function ($query) use ($yardName) {
                $query->where('name', $yardName);
            });
        } elseif ($yardName === 'Sân không tồn tại') {
            $orders->whereDoesntHave('orderDetails.yard');
        }

        // Lọc theo loại sân
        if ($typeName && $typeName !== 'Loại sân không tồn tại') {
            $orders->whereHas('orderDetails.yard.type', function ($query) use ($typeName) {
                $query->where('name', $typeName);
            });
        } elseif ($typeName === 'Loại sân không tồn tại') {
            $orders->whereHas('orderDetails.yard', function ($query) {
                $query->whereNull('type_id');
            })->orWhereDoesntHave('orderDetails.yard');
        }

        // Lọc theo trạng thái
        if (!is_null($status)) {
            $orders->where('status', $status);
        }

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

        $order = Order::with('orderDetails')->findOrFail($order_id);
        $newStatus = (int) $request->status;

        // Cập nhật trạng thái mới cho đơn hiện tại
        $order->status = $newStatus;
        $order->save();

        // Nếu admin chọn "Xác nhận" => tự động hủy các đơn khác trùng sân, ngày, khung giờ
        if ($newStatus === 1) {
            foreach ($order->orderDetails as $detail) {
                // Lấy tất cả đơn khác trùng khung giờ và sân
                $conflictOrders = \App\Models\OrderDetail::where('yard_id', $detail->yard_id)
                    ->where('date', $detail->date)
                    ->where('time', $detail->time)
                    ->where('order_id', '!=', $order_id)
                    ->pluck('order_id');

                if ($conflictOrders->count()) {
                    \App\Models\Order::whereIn('order_id', $conflictOrders)
                        ->where('status', '!=', 2) // chỉ cập nhật nếu chưa bị hủy
                        ->update(['status' => 2]);
                }
            }
        }

        return redirect()->route('quan-ly-don-dat-san')
            ->with('success', 'Cập nhật trạng thái đơn đặt sân thành công !');
    }

    public function delete($order_id)
    {
        $order = Order::find($order_id);
        if ($order) {
            $order->delete();
            return redirect()->route('quan-ly-don-dat-san')->with('success', 'Xóa đơn đơn đặt sân thành công !');
        }
        return redirect()->route('quan-ly-don-dat-san')->with('error', 'Không tìm thấy đơn đơn đặt sân.');
    }
}
