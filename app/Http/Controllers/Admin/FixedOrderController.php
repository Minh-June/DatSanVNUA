<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonthRent;
use Illuminate\Support\Facades\Auth;

class FixedOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Ngày mặc định là hôm nay nếu không chọn
        $selectedDate = $request->input('selected_date', now()->toDateString());

        $orders = MonthRent::with(['yard', 'user'])
        // Admin + Chủ sân: xem sân của họ
        ->when(in_array($user->role, [0, 2]), function($q) use ($user) {
            $q->whereHas('yard', fn($q2) => $q2->where('user_id', $user->user_id));
        })
        // Nhân viên: xem sân thuộc manager_id
        ->when($user->role == 3, function($q) use ($user) {
            $q->whereHas('yard', fn($q2) => $q2->where('user_id', $user->manager_id));
        })
        // Lọc theo ngày đặt khách đăng ký
        ->whereDate('date', $selectedDate)
        ->orderBy('date', 'asc')
        ->get();

        // Gửi dữ liệu đến view
        return view('admin.fixedorder.index', compact('orders', 'selectedDate'));
    }

    public function updateStatus(Request $request, $order_id)
    {
        $request->validate([
            'status' => 'required|in:0,1,2,3', // 0=Chờ,1=Xác nhận,2=Hủy,3=Đặt cọc
        ]);

        $order = MonthRent::findOrFail($order_id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success','Cập nhật trạng thái thành công!');
    }

    public function edit($id)
    {
        $order = MonthRent::with('yard','user')->findOrFail($id);
        return view('admin.fixedorder.update', compact('order'));
    }

    public function delete($id)
    {
        $order = MonthRent::findOrFail($id);
        $order->delete();

        return redirect()->back()->with('success','Xóa đơn thuê cố định thành công!');
    }
}
