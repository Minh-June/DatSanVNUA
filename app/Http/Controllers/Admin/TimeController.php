<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Time;
use App\Models\Yard;

class TimeController extends Controller
{
    public function index(Request $request)
    {
        $query = Time::join('yards', 'times.yard_id', '=', 'yards.yard_id')
                     ->orderBy('yards.name', 'asc');
    
        $yards = Yard::orderBy('name', 'asc')->get();
    
        if ($request->has('yard_id') && $request->yard_id != '') {
            $yard_id = $request->yard_id;
            $date = $request->date ?? date('Y-m-d');
    
            $query->where('times.yard_id', $yard_id)
                  ->whereDate('times.date', $date);
    
            $times = $query->get();
    
            // Nếu chưa có dữ liệu cho ngày được chọn, tự động sao chép ngày gần nhất
            if ($times->isEmpty()) {
                // Tìm ngày gần nhất có dữ liệu của sân này, trước ngày được chọn
                $latestDate = Time::where('yard_id', $yard_id)
                                ->whereDate('date', '<', $date)
                                ->orderBy('date', 'desc')
                                ->limit(1)
                                ->value('date');
    
                if ($latestDate) {
                    $latestTimes = Time::where('yard_id', $yard_id)
                                       ->whereDate('date', $latestDate)
                                       ->orderBy('time_id')
                                       ->get();
    
                    foreach ($latestTimes as $time) {
                        Time::create([
                            'yard_id' => $time->yard_id,
                            'time'    => $time->time,
                            'price'   => $time->price,
                            'date'    => $date,
                        ]);
                    }
    
                    // Sau khi sao chép xong, lấy lại dữ liệu để hiển thị
                    $times = Time::join('yards', 'times.yard_id', '=', 'yards.yard_id')
                                 ->where('times.yard_id', $yard_id)
                                 ->whereDate('times.date', $date)
                                 ->orderBy('yards.name', 'asc')
                                 ->get();
                }
            }
    
            return view('admin.timeyards.index', compact('times', 'yards'));
        }
    
        $times = $query->get();
        return view('admin.timeyards.index', compact('times', 'yards'));
    }

    // Hiển thị form thêm thời gian
    public function create()
    {
        $yards = Yard::orderBy('name')->get(); // Lấy danh sách sân
        return view('admin.timeyards.create', compact('yards'));
    }

    // Lưu thời gian mới
    public function store(Request $request)
    {
        $request->validate([
            'yard_id' => 'required|exists:yards,yard_id',
            'time' => 'required|string',
            'price' => 'required|numeric|min:0',
            'date' => 'required|date', 
        ]);
    
        // Lưu thông tin khung giờ mới
        Time::create([
            'yard_id' => $request->yard_id,
            'time' => $request->time,
            'price' => $request->price,
            'date' => $request->date, 
        ]);
    
        // Chuyển hướng về trang quản lý khung giờ với sân đã chọn
        return redirect()->route('quan-ly-thoi-gian-san', ['yard_id' => $request->yard_id])
                         ->with('success', 'Thêm khung giờ cho thuê thành công!');
    }

    // Hiển thị form cập nhật
    public function edit($time_id)
    {
        $time = Time::findOrFail($time_id);
        $yards = Yard::orderBy('name')->get();

        return view('admin.timeyards.update', compact('time', 'yards'));
    }

    // Cập nhật khung giờ
    public function update(Request $request, $time_id)
    {
        $request->validate([
            'yard_id' => 'required|exists:yards,yard_id',
            'time' => 'required|string',
            'price' => 'required|numeric|min:0',
            'date' => 'required|date', // Kiểm tra ngày hợp lệ
        ]);
    
        $time = Time::findOrFail($time_id);
        $old_date = $time->date; // Lưu ngày cũ
    
        // Cập nhật khung giờ và giá cho ngày hiện tại
        $time->update([
            'yard_id' => $request->yard_id,
            'time' => $request->time,
            'price' => $request->price,
            'date' => $request->date, // Cập nhật ngày
        ]);
    
        // Cập nhật tất cả các ngày sau ngày cập nhật với khung giờ và giá mới
        Time::where('yard_id', $request->yard_id)
            ->where('time', $time->time)  // Cùng thời gian
            ->whereDate('date', '>', $request->date) // Chọn ngày sau ngày cập nhật
            ->update([
                'time' => $request->time,
                'price' => $request->price,
            ]);
    
        // Tạo các bản ghi tự động cho các ngày tiếp theo dựa trên time_id của ngày trước đó
        $nextDate = date('Y-m-d', strtotime($request->date . ' +1 day')); // Ngày tiếp theo
    
        // Nếu không có dữ liệu cho ngày tiếp theo, sao chép từ ngày trước
        $nextTime = Time::where('yard_id', $request->yard_id)
                        ->whereDate('date', $nextDate)
                        ->where('time', $time->time) // Cùng khung giờ
                        ->first();
    
        if (!$nextTime) {
            // Sao chép thông tin từ ngày hiện tại sang ngày tiếp theo
            Time::create([
                'yard_id' => $time->yard_id,
                'time' => $request->time,
                'price' => $request->price,
                'date' => $nextDate,
            ]);
        }
    
        // Chuyển hướng về trang quản lý khung giờ với sân đã chọn
        return redirect()->route('quan-ly-thoi-gian-san', ['yard_id' => $request->yard_id])
                         ->with('success', 'Cập nhật thời gian thành công!');
    }
    

    // Xóa khung giờ
    public function delete(Request $request, $time_id)
    {
        $time = Time::findOrFail($time_id);
        $time->delete();
    
        // Chuyển hướng về trang quản lý khung giờ với sân đã chọn
        return redirect()
            ->route('quan-ly-thoi-gian-san', ['yard_id' => $request->yard_id])
            ->with('success', 'Xóa khung giờ thành công!');
    }
}
