<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\PayRequest;
use Illuminate\Http\Request;
use App\Models\Order;

class PayController extends Controller
{
    public function index(Request $request)
    {
        // Lấy thông tin từ session
        $payInfo = [
            'name' => session('name'),
            'phone' => session('phone'),
            'tensan' => session('tensan'),
            'sosan' => session('sosan'),
            'time' => session('time'),
            'date' => session('date'),
            'price' => session('price'),
            'notes' => session('notes'),
        ];

        // Chuyển thông tin sang view pay.blade.php
        return view('client.pay', compact('payInfo'));
    }
    
    public function store(PayRequest  $request)
    {
        // Lưu thông tin từ session
        $orderData = session()->only(['name', 'phone', 'san_id', 'time', 'date', 'price', 'notes']);
        
        // Lấy user_id từ session
        $userId = session('user_id');
    
        // Tạo một mảng để lưu tên file
        $imagePaths = [];
    
        // Lưu ảnh
        if ($request->hasfile('images')) {
            foreach ($request->file('images') as $image) {
                // Lưu ảnh vào thư mục public/bills và lấy tên file gốc
                $path = $image->storeAs('bills', $image->getClientOriginalName(), 'public');
                $imagePaths[] = $path; // Lưu đường dẫn của ảnh đã lưu
            }
        }
    
        // Lưu thông tin vào bảng tbl_order
        $order = new Order();
        $order->name = $orderData['name'];
        $order->phone = $orderData['phone'];
        $order->san_id = $orderData['san_id']; // Sử dụng san_id
        $order->user_id = $userId; // Lưu user_id
        $order->date = $orderData['date'];
        $order->time = implode(',', $orderData['time']); // Chuyển mảng thành chuỗi phân cách bằng dấu phẩy
        $order->price = $orderData['price'];
        $order->notes = $orderData['notes'];
        $order->image = json_encode($imagePaths); // lưu đường dẫn ảnh
        $order->save();
    
        // Xóa thông tin khỏi session (nếu cần)
        session()->forget(['name', 'phone', 'san_id', 'time', 'date', 'price', 'notes']);
    
        return redirect()->route('trang-chu')->with('success', 'Bạn đã đặt sân thành công !');
    }
    
}
