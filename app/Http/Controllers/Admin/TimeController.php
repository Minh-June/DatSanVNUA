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
    
            // Náº¿u chÆ°a cĂ³ dá»¯ liá»‡u cho ngĂ y Ä‘Æ°á»£c chá»n, tá»± Ä‘á»™ng sao chĂ©p ngĂ y gáº§n nháº¥t
            if ($times->isEmpty()) {
                // TĂ¬m ngĂ y gáº§n nháº¥t cĂ³ dá»¯ liá»‡u cá»§a sĂ¢n nĂ y, trÆ°á»›c ngĂ y Ä‘Æ°á»£c chá»n
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
    
                    // Sau khi sao chĂ©p xong, láº¥y láº¡i dá»¯ liá»‡u Ä‘á»ƒ hiá»ƒn thá»‹
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

    // Hiá»ƒn thá»‹ form thĂªm thá»i gian
    public function create()
    {
        $yards = Yard::orderBy('name')->get(); // Láº¥y danh sĂ¡ch sĂ¢n
        return view('admin.timeyards.create', compact('yards'));
    }

    // LÆ°u thá»i gian má»›i
    public function store(Request $request)
    {
        $request->validate([
            'yard_id' => 'required|exists:yards,yard_id',
            'time' => 'required|string',
            'price' => 'required|numeric|min:0',
            'date' => 'required|date', 
        ]);
    
        // LÆ°u thĂ´ng tin khung giá» má»›i
        Time::create([
            'yard_id' => $request->yard_id,
            'time' => $request->time,
            'price' => $request->price,
            'date' => $request->date, 
        ]);
    
        // Chuyá»ƒn hÆ°á»›ng vá» trang quáº£n lĂ½ khung giá» vá»›i sĂ¢n Ä‘Ă£ chá»n
        return redirect()->route('quan-ly-thoi-gian-san', ['yard_id' => $request->yard_id])
                         ->with('success', 'ThĂªm khung giá» cho thuĂª thĂ nh cĂ´ng!');
    }

    // Hiá»ƒn thá»‹ form cáº­p nháº­t
    public function edit($time_id)
    {
        $time = Time::findOrFail($time_id);
        $yards = Yard::orderBy('name')->get();

        return view('admin.timeyards.update', compact('time', 'yards'));
    }

    // Cáº­p nháº­t khung giá»
    public function update(Request $request, $time_id)
    {
        $request->validate([
            'yard_id' => 'required|exists:yards,yard_id',
            'time' => 'required|string',
            'price' => 'required|numeric|min:0',
            'date' => 'required|date', // Kiá»ƒm tra ngĂ y há»£p lá»‡
        ]);
    
        $time = Time::findOrFail($time_id);
        $old_date = $time->date; // LÆ°u ngĂ y cÅ©
    
        // Cáº­p nháº­t khung giá» vĂ  giĂ¡ cho ngĂ y hiá»‡n táº¡i
        $time->update([
            'yard_id' => $request->yard_id,
            'time' => $request->time,
            'price' => $request->price,
            'date' => $request->date, // Cáº­p nháº­t ngĂ y
        ]);
    
        // Cáº­p nháº­t táº¥t cáº£ cĂ¡c ngĂ y sau ngĂ y cáº­p nháº­t vá»›i khung giá» vĂ  giĂ¡ má»›i
        Time::where('yard_id', $request->yard_id)
            ->where('time', $time->time)  // CĂ¹ng thá»i gian
            ->whereDate('date', '>', $request->date) // Chá»n ngĂ y sau ngĂ y cáº­p nháº­t
            ->update([
                'time' => $request->time,
                'price' => $request->price,
            ]);
    
        // Táº¡o cĂ¡c báº£n ghi tá»± Ä‘á»™ng cho cĂ¡c ngĂ y tiáº¿p theo dá»±a trĂªn time_id cá»§a ngĂ y trÆ°á»›c Ä‘Ă³
        $nextDate = date('Y-m-d', strtotime($request->date . ' +1 day')); // NgĂ y tiáº¿p theo
    
        // Náº¿u khĂ´ng cĂ³ dá»¯ liá»‡u cho ngĂ y tiáº¿p theo, sao chĂ©p tá»« ngĂ y trÆ°á»›c
        $nextTime = Time::where('yard_id', $request->yard_id)
                        ->whereDate('date', $nextDate)
                        ->where('time', $time->time) // CĂ¹ng khung giá»
                        ->first();
    
        if (!$nextTime) {
            // Sao chĂ©p thĂ´ng tin tá»« ngĂ y hiá»‡n táº¡i sang ngĂ y tiáº¿p theo
            Time::create([
                'yard_id' => $time->yard_id,
                'time' => $request->time,
                'price' => $request->price,
                'date' => $nextDate,
            ]);
        }
    
        // Chuyá»ƒn hÆ°á»›ng vá» trang quáº£n lĂ½ khung giá» vá»›i sĂ¢n Ä‘Ă£ chá»n
        return redirect()->route('quan-ly-thoi-gian-san', ['yard_id' => $request->yard_id])
                         ->with('success', 'Cáº­p nháº­t thá»i gian thĂ nh cĂ´ng!');
    }
    

    // XĂ³a khung giá»
    public function delete(Request $request, $time_id)
    {
        $time = Time::findOrFail($time_id);
        $time->delete();
    
        // Chuyá»ƒn hÆ°á»›ng vá» trang quáº£n lĂ½ khung giá» vá»›i sĂ¢n Ä‘Ă£ chá»n
        return redirect()
            ->route('quan-ly-thoi-gian-san', ['yard_id' => $request->yard_id])
            ->with('success', 'XĂ³a khung giá» thĂ nh cĂ´ng!');
    }
}
