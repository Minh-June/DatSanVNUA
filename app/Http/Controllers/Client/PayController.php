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
        // Láº¥y danh sĂ¡ch Ä‘Æ¡n hĂ ng tá»« session
        $orders = session('orders', []);

        // Truyá»n danh sĂ¡ch nĂ y sang view
        return view('client.pay', compact('orders'));
    }
    
    public function store(PayRequest $request)
    {
        $orders = session('orders', []);
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Vui lĂ²ng Ä‘Äƒng nháº­p trÆ°á»›c khi thanh toĂ¡n.');
        }

        if (empty($orders)) {
            return redirect()->back()->with('error', 'KhĂ´ng cĂ³ Ä‘Æ¡n Ä‘áº·t sĂ¢n nĂ o Ä‘á»ƒ thanh toĂ¡n.');
        }

        // LÆ°u áº£nh thanh toĂ¡n
        $imagePaths = [];
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->storeAs('bills', uniqid() . '_' . $image->getClientOriginalName(), 'public');
                $imagePaths[] = $path;
            }
        }

        // Táº¡o Ä‘Æ¡n hĂ ng tá»•ng
        $order = new Order();
        $order->user_id = $userId;
        $order->name = $orders[0]['name'];
        $order->phone = $orders[0]['phone'];
        $order->date = now();
        $order->status = 0;
        $order->image = json_encode($imagePaths);
        $order->save();

        // LÆ°u chi tiáº¿t tá»«ng sĂ¢n, má»—i khung giá» 1 dĂ²ng order_detail riĂªng
        foreach ($orders as $item) {
            foreach ($item['times'] as $index => $time) {
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->order_id;
                $orderDetail->yard_id = $item['yard_id'];
                $orderDetail->date = $item['date'];
                $orderDetail->time = $time;   // Má»—i dĂ²ng chá»‰ lÆ°u 1 khung giá»

                // Láº¥y Ä‘Ăºng giĂ¡ cho tá»«ng khung giá» theo index
                $pricePerTime = $item['price_per_slot'][$index] ?? 0;
                $orderDetail->price = $pricePerTime;

                $orderDetail->notes = $item['notes'] ?? null;
                $orderDetail->save();
            }
        }

        session()->forget('orders');

        return redirect()->route('trang-chu')->with('success', 'Báº¡n Ä‘Ă£ Ä‘áº·t sĂ¢n thĂ nh cĂ´ng !');
    }

}
