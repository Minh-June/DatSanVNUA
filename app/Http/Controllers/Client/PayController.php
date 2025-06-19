<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\PayRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Type; 
use App\Models\OrderDetail;

class PayController extends Controller
{
    public function index()
    {
        $orders = session('orders', []);
        $types = Type::all();

        session(['orders' => $orders]);

        return view('client.pay', compact('orders', 'types'));
    }
    
    public function store(PayRequest $request)
    {
        $orders = session('orders', []);
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập trước khi thanh toán.');
        }

        if (empty($orders)) {
            return redirect()->back()->with('error', 'Không có đơn đặt sân nào để thanh toán.');
        }

        // Lưu ảnh thanh toán
        $imagePaths = [];
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->storeAs('bills', uniqid() . '_' . $image->getClientOriginalName(), 'public');
                $imagePaths[] = $path;
            }
        }

        // Tạo đơn hàng tổng
        $order = new Order();
        $order->user_id = $userId;
        $order->name = $orders[0]['name'];
        $order->phone = $orders[0]['phone'];
        $order->date = now();
        $order->status = 0;
        $order->image = json_encode($imagePaths);
        $order->save();

        // Lưu chi tiết từng sân
        foreach ($orders as $item) {
            foreach ($item['times'] as $index => $time) {
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->order_id;
                $orderDetail->yard_id = $item['yard_id'];
                $orderDetail->type_id = $item['type_id'];
                $orderDetail->date = $item['date'];
                $orderDetail->time = $time;
                $orderDetail->price = $item['price_per_slot'][$index] ?? 0;
                $orderDetail->notes = $item['notes'] ?? null;
                $orderDetail->save();
            }
        }

        session()->forget('orders');

        // Điều hướng theo role
        if (auth()->user()->role != 1) {
            return redirect()->route('quan-ly-don-dat-san')->with('success', 'Bạn đã đặt sân thành công với tư cách quản trị viên !');
        }

        return redirect()->route('trang-chu')->with('success', 'Đặt sân thành công, vui lòng chờ chủ sân xác nhận !');
    }
}
