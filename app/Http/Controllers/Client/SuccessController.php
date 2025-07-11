<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;

class SuccessController extends Controller
{
    public function index()
    {
        $orders = session('orders', []);
        $types = Type::all(); // Nếu bạn cần danh sách loại sân cho view

        session(['orders' => $orders]);

        return view('client.success', compact('orders', 'types'));
    }

    public function delete(Request $request)
    {
        // Lấy danh sách đơn hàng từ session
        $orders = session('orders', []);
        
        // Lấy index của đơn hàng cần xóa
        $index = $request->input('index');
        
        // Kiểm tra nếu tồn tại đơn hàng tại index này
        if (isset($orders[$index])) {
            // Xóa đơn hàng khỏi session
            unset($orders[$index]);
            
            // Cập nhật lại session với danh sách đơn hàng đã xóa
            session(['orders' => array_values($orders)]); // array_values() để reset lại chỉ số mảng
        }

        // Quay lại trang danh sách đơn hàng sau khi xóa
        return redirect()->route('xac-nhan-dat-san');
    }
}
