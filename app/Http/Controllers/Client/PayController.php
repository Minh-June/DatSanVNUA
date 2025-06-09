<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\PayRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;

class PayController extends Controller
{
    public function index()
    {
        // Lấy danh sách đơn hàng từ session
        $orders = session('orders', []);

        // Truyền danh sách này sang view
        return view('client.pay', compact('orders'));
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

        // Lưu chi tiết từng sân, mỗi khung giờ 1 dòng order_detail riêng
        foreach ($orders as $item) {
            foreach ($item['times'] as $index => $time) {
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->order_id;
                $orderDetail->yard_id = $item['yard_id'];
                $orderDetail->date = $item['date'];
                $orderDetail->time = $time;   // Mỗi dòng chỉ lưu 1 khung giờ

                // Lấy đúng giá cho từng khung giờ theo index
                $pricePerTime = $item['price_per_slot'][$index] ?? 0;
                $orderDetail->price = $pricePerTime;

                $orderDetail->notes = $item['notes'] ?? null;
                $orderDetail->save();
            }
        }

        session()->forget('orders');

        return redirect()->route('trang-chu')->with('success', 'Bạn đã đặt sân thành công !');
    }

}
