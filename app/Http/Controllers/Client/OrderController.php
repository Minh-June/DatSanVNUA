<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Client\OrderRequest;
use App\Models\Yard;

class OrderController extends Controller
{
    public function index()
    {
        // Lấy thông tin từ session với các khóa nhất quán
        $bookingInfo = session()->only(['user_id', 'san_id', 'name', 'phone', 'notes', 'time', 'date', 'price']);
    
        // Lấy thông tin sân từ database
        $san = Yard::find($bookingInfo['san_id']);
    
        // Thêm tên sân và số sân vào thông tin đặt
        $bookingInfo['tensan'] = $san->tensan;
        $bookingInfo['sosan'] = $san->sosan;  
    
        return view('client.success', $bookingInfo);
    }    
    
    public function store(OrderRequest $request) // Sử dụng OrderRequest
    {
        // Lấy thông tin sân từ database
        $san = Yard::find($request->input('san_id'));
    
        // Chuyển đổi selected_times thành mảng
        $selected_times = explode(',', $request->input('selected_times'));
    
        // Lưu các trường vào session với các khóa nhất quán
        session([
            'user_id' => $request->input('user_id'),
            'san_id' => $san->san_id,
            'tensan' => $san->tensan,
            'sosan' => $san->sosan,
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'notes' => $request->input('notes'),
            'time' => $selected_times, 
            'date' => $request->input('date'),
            'price' => $request->input('total_price'), // Sử dụng 'price' thay vì 'total_price'
        ]);
    
        return redirect()->route('xac-nhan-dat-san');
    }  

}