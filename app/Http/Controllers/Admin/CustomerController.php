<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\StoreRequest;
use App\Http\Requests\Admin\Customer\UpdateRequest;
use App\Http\Requests\Admin\Customer\UpdateStatusRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Yard;

class CustomerController extends Controller
{
    
    public function index(Request $request)
    {
        // Kiểm tra xem có ngày nào được chọn không
        $selectedDate = $request->get('selected_date');
    
        // Lấy tất cả đơn hàng và sắp xếp theo ngày từ mới đến cũ
        if ($selectedDate) {
            $orders = Order::where('date', $selectedDate)->orderBy('date', 'desc')->get();
        } else {
            $orders = Order::orderBy('date', 'desc')->get();
        }
    
        // Lấy ngày hiện tại
        $today = date('Y-m-d');
        
        return view('admin.orders.index', compact('orders', 'today', 'selectedDate'));
    }

    public function updateStatus(UpdateStatusRequest $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $request->input('status');
        $order->save();
    
        return redirect()->route('quan-ly-khach-hang')->with('success', 'Trạng thái đơn hàng đã được cập nhật!');
    }
    
    public function create()
    {
        $sans = Yard::all(); // Lấy danh sách sân để hiển thị trong dropdown
        return view('admin.orders.create', compact('sans')); // Chuyển thông tin đến view
    }

    public function store(StoreRequest $request) // Sử dụng StoreRequest
    {
        // Tạo đơn hàng mới
        Order::create(array_merge($request->validated(), ['user_id' => null])); // Admin tự đặt đơn nên để user_id = 1
        
        return redirect()->route('quan-ly-khach-hang')->with('success', 'Thêm khách hàng mới thành công!');
    }
    
    public function edit($id)
    {
        $order = Order::findOrFail($id); // Tìm đơn hàng theo ID
        $sans = Yard::all(); // Lấy danh sách sân để hiển thị trong dropdown
        return view('admin.orders.update', compact('order', 'sans')); // Chuyển thông tin đến view
    }
    
    public function update(UpdateRequest $request, $order_id) // Sử dụng UpdateRequest
    {
        $order = Order::findOrFail($order_id); // Tìm đơn hàng theo ID
        $order->update($request->validated()); // Cập nhật thông tin đơn hàng
        
        return redirect()->route('quan-ly-khach-hang')->with('success', 'Cập nhật thông tin khách hàng thành công!'); // Chuyển hướng với thông báo thành công
    }
    
    public function delete($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete(); // Xóa đơn hàng
            return redirect()->route('quan-ly-khach-hang')->with('success', 'Xóa đơn hàng thành công.');
        }
        return redirect()->route('quan-ly-khach-hang')->with('error', 'Không tìm thấy đơn hàng.');
    }
    
}