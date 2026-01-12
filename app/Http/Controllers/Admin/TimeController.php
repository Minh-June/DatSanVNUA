<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\TimeYard\StoreRequest;
use App\Http\Requests\Admin\TimeYard\UpdateRequest;
use App\Models\Time;
use App\Models\Yard;
use Carbon\Carbon;

class TimeController extends Controller
{
    public function index(Request $request)
    {
        $yard_id = $request->yard_id;
        $times = collect();
        $yard = null;
        $canManage = false;

        if ($yard_id) {
            $yard = Yard::with('type', 'user')->find($yard_id);
            $times = Time::where('yard_id', $yard_id)
                        ->orderBy('start', 'asc')
                        ->get();

            $user = auth()->user();
            $owner = $yard->user ?? null;

            if($owner) {
                if($user->role == 0 && $owner->role == 0 && $owner->user_id == $user->user_id) {
                    $canManage = true; // admin xem sân của chính họ
                } elseif($user->role == 2 && $owner->user_id == $user->user_id) {
                    $canManage = true; // chủ sân xem sân của mình
                }
                // role=3 luôn false
            }
        }

        return view('admin.timeyards.index', compact('times', 'yard', 'yard_id', 'canManage'));
    }

    public function updateStatus(Request $request, $_id)
    {
        $time = Time::findOrFail($_id);
        $time->status = $request->status; // 1 = hiển thị, 0 = ẩn
        $time->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công !');
    }

    public function create(Request $request)
    {
        $yards = Yard::orderBy('name')->get();
        $yard_id = $request->yard_id;
        return view('admin.timeyards.create', compact('yards', 'yard_id'));
    }

    public function store(StoreRequest $request)
    {
        $yard_id = $request->yard_id;
        $start = Carbon::parse($request->start)->format('H:i');
        $end   = Carbon::parse($request->end)->format('H:i');

        // Kiểm tra trùng hoặc chồng khung giờ
        $exists = Time::where('yard_id', $yard_id)
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end])
                    ->orWhere(function($q) use ($start, $end) {
                        $q->where('start', '<=', $start)
                            ->where('end', '>=', $end);
                    });
            })
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['start' => 'Khung giờ này trùng hoặc chồng lên khung giờ đã có.']);
        }

        Time::create([
            'yard_id'       => $yard_id,
            'start'         => $start,
            'end'           => $end,
            'price_weekday' => $request->price_weekday ?: null,
            'price_weekend' => $request->price_weekend ?: null,
            'is_classic'    => 0,
            'status'        => 0,
        ]);

        return redirect()
            ->route('quan-ly-thoi-gian-san', ['yard_id' => $yard_id])
            ->with('success', 'Thêm khung giờ thành công !');
    }

    public function edit($time_id)
    {
        $time = Time::findOrFail($time_id);
        $yards = Yard::orderBy('name')->get();
        $yard_id = $time->yard_id;
        return view('admin.timeyards.update', compact('time', 'yards', 'yard_id'));
    }

    public function update(UpdateRequest $request, $time_id)
    {
        $time = Time::findOrFail($time_id);
        $start = Carbon::parse($request->start)->format('H:i');
        $end   = Carbon::parse($request->end)->format('H:i');

        // Kiểm tra trùng hoặc chồng khung giờ (bỏ qua chính khung này)
        $exists = Time::where('yard_id', $time->yard_id)
            ->where('time_id', '<>', $time_id)
            ->where(function($query) use ($start, $end) {
                $query->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end])
                    ->orWhere(function($q) use ($start, $end) {
                        $q->where('start', '<=', $start)
                            ->where('end', '>=', $end);
                    });
            })
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['start' => 'Khung giờ này trùng hoặc chồng lên khung giờ đã có.']);
        }

        $time->update([
            'start'         => $start,
            'end'           => $end,
            'price_weekday' => $request->price_weekday ?: null,
            'price_weekend' => $request->price_weekend ?: null,
        ]);

        return redirect()
            ->route('quan-ly-thoi-gian-san', ['yard_id' => $time->yard_id])
            ->with('success', 'Cập nhật khung giờ thành công !');
    }

    public function delete(Request $request, $time_id)
    {
        $time = Time::findOrFail($time_id);
        $yard_id = $time->yard_id;
        $time->delete();

        return redirect()
            ->route('quan-ly-thoi-gian-san', ['yard_id' => $yard_id])
            ->with('success', 'Xóa khung giờ thành công !');
    }
}
