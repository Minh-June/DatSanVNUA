<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\TimeYard\UpdateRequest;
use App\Models\Timeslot;
use App\Models\Yard;

class TimeYardController extends Controller
{
    public function index(Request $request)
    {
        // Lấy danh sách sân từ cơ sở dữ liệu
        $san_list = Yard::all();

        // Truyền danh sách sân đến view
        return view('admin.timeyards.index', compact('san_list'));
    }

    public function search(Request $request)
    {
        $san_id = $request->input('san_id');
        $time_slots = Timeslot::where('san_id', $san_id)->get();
    
        // Truyền san_id đã chọn về view
        return view('admin.timeyards.index', [
            'san_list' => Yard::all(),
            'time_slots' => $time_slots,
            'selected_san_id' => $san_id,  // thêm biến này
        ]);
    }

    public function delete($id)
    {
        // Tìm khung giờ theo ID
        $timeSlot = Timeslot::find($id);

        // Nếu khung giờ tồn tại, thực hiện xóa
        if ($timeSlot) {
            $timeSlot->delete();
            return redirect()->route('quan-ly-thoi-gian-san')->with('success', 'Khung giờ đã được xóa thành công !');
        }

        // Nếu không tìm thấy khung giờ
        return redirect()->route('quan-ly-thoi-gian-san')->with('error', 'Khung giờ không tồn tại !');
    }
    
    public function edit($id)
    {
        // Tìm khung giờ theo ID
        $timeSlot = Timeslot::find($id);
    
        // Nếu khung giờ tồn tại, truyền dữ liệu đến view
        if ($timeSlot) {
            return view('admin.timeyards.update', ['time_slot' => $timeSlot]);
        }
    
        // Nếu không tìm thấy khung giờ, có thể chuyển hướng về trang quản lý hoặc trả về lỗi
        return redirect()->route('quan-ly-thoi-gian-san')->with('error', 'Khung giờ không tồn tại!');
    }
    
    public function update(UpdateRequest $request, $id)
    {
        // Tìm khung giờ theo ID
        $timeSlot = Timeslot::find($id);
    
        // Nếu khung giờ tồn tại, cập nhật thông tin
        if ($timeSlot) {
            $timeSlot->time_slot = $request->input('new_time_slot');
            $timeSlot->price = $request->input('price');
            $timeSlot->save();
    
            return redirect()->route('quan-ly-thoi-gian-san')->with('success', 'Khung giờ đã được cập nhật thành công!');
        }
    
        return redirect()->route('quan-ly-thoi-gian-san')->with('error', 'Khung giờ không tồn tại!');
    }

    public function create()
    {
        // Lấy danh sách sân từ cơ sở dữ liệu
        $san_list = Yard::all();

        // Truyền danh sách sân đến view
        return view('admin.timeyards.create', compact('san_list'));
    }

    public function store(Request $request)
    {
        // Lưu thông tin khung giờ mới vào cơ sở dữ liệu
        Timeslot::create([
            'san_id' => $request->input('san_id'),
            'time_slot' => $request->input('time_slot'),
            'price' => $request->input('price'),
        ]);
    
        return redirect()->route('quan-ly-thoi-gian-san')->with('success', 'Khung giờ đã được thêm thành công!');
    }
}
