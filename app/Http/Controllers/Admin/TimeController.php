<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\TimeYard\StoreRequest;
use App\Http\Requests\Admin\TimeYard\UpdateRequest;
use App\Models\Time;
use App\Models\Yard;

class TimeController extends Controller
{
    public function index(Request $request)
    {
        $yards = Yard::with('type')->orderBy('name', 'asc')->get(); // Thêm with('type') nếu cần
        $yard_id = $request->yard_id;
        $date = $request->date ?? date('Y-m-d');
        $times = collect(); // Mặc định rỗng
        $yard = null; // Thêm biến yard để truyền sang view

        if ($yard_id) {
            $yard = Yard::with('type')->find($yard_id); // Truyền sang view để lấy tên và loại

            $times = Time::join('yards', 'times.yard_id', '=', 'yards.yard_id')
                        ->where('times.yard_id', $yard_id)
                        ->whereDate('times.date', $date)
                        ->orderBy('times.time', 'asc')
                        ->select('times.*')
                        ->get();

            if ($times->isEmpty()) {
                $yesterday = date('Y-m-d', strtotime($date . ' -1 day'));
                Time::cloneFromDateToDate($yard_id, $yesterday, $date);

                $times = Time::join('yards', 'times.yard_id', '=', 'yards.yard_id')
                            ->where('times.yard_id', $yard_id)
                            ->whereDate('times.date', $date)
                            ->orderBy('times.time', 'asc')
                            ->select('times.*')
                            ->get();
            }
        }

        $isPastDate = strtotime($date) < strtotime(date('Y-m-d'));

        return view('admin.timeyards.index', compact('times', 'yards', 'yard_id', 'date', 'isPastDate', 'yard'));
    }

    public function create(Request $request)
    {
        $yards = Yard::orderBy('name')->get();
        $yard_id = $request->yard_id;
        return view('admin.timeyards.create', compact('yards', 'yard_id'));
    }

    public function store(StoreRequest $request)
    {
        Time::create([
            'yard_id' => $request->yard_id,
            'time'    => $request->time,
            'price'   => $request->price,
            'date'    => $request->date,
        ]);

        return redirect()
            ->route('quan-ly-thoi-gian-san', ['yard_id' => $request->yard_id])
            ->with('success', 'Thêm khung giờ cho thuê thành công!');
    }

    // Hiển thị form cập nhật
    public function edit($time_id)
    {
        $time = Time::findOrFail($time_id);
        $yards = Yard::orderBy('name')->get();

        return view('admin.timeyards.update', compact('time', 'yards'));
    }

    public function update(UpdateRequest $request, $time_id)
    {
        $time = Time::findOrFail($time_id);

        $time->update([
            'yard_id' => $request->yard_id,
            'time' => $request->time,
            'price' => $request->price,
            'date' => $request->date,
        ]);

        // Cập nhật tất cả các ngày sau với cùng khung giờ
        Time::where('yard_id', $request->yard_id)
            ->where('time', $time->time)
            ->whereDate('date', '>', $request->date)
            ->update([
                'time' => $request->time,
                'price' => $request->price,
            ]);

        // Tạo bản ghi cho ngày kế tiếp nếu chưa có
        $nextDate = date('Y-m-d', strtotime($request->date . ' +1 day'));
        $nextTime = Time::where('yard_id', $request->yard_id)
            ->where('date', $nextDate)
            ->where('time', $request->time)
            ->first();

        if (!$nextTime) {
            Time::create([
                'yard_id' => $request->yard_id,
                'time' => $request->time,
                'price' => $request->price,
                'date' => $nextDate,
            ]);
        }

        return redirect()
            ->route('quan-ly-thoi-gian-san', ['yard_id' => $request->yard_id])
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
