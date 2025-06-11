<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Yard;
use App\Models\Time;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->get('selected_date');

        $orders = Order::with(['orderDetails.yard'])
            ->when($selectedDate, function ($query, $selectedDate) {
                return $query->whereHas('orderDetails', function ($q) use ($selectedDate) {
                    $q->whereDate('date', $selectedDate);
                });
            })
            ->orderBy('date', 'desc')
            ->get();

        foreach ($orders as $order) {
            $order->groupedDetails = $order->orderDetails->groupBy(function ($item) {
                return $item->date . '|' . (trim($item->notes) ?: 'no_notes') . '|' . $item->yard_id;
            })->map(function ($group) {
                return [
                    'date' => $group->first()->date,
                    'notes' => $group->first()->notes,
                    'price' => $group->sum('price'),
                    'yard' => $group->first()->yard,
                    'times' => implode(', ', $group->pluck('time')->toArray()),
                ];
            })->values();
        }

        return view('admin.orders.index', compact('orders', 'selectedDate'));
    }

    public function create(Request $request)
    {
        $yards = Yard::all();

        $yardId = $request->input('yard_id');
        $date = $request->input('date');

        $timesForSelectedDate = [];

        if ($yardId && $date) {
            $times = Time::where('yard_id', $yardId)->where('date', $date)->get();

            $bookedTimes = OrderDetail::where('yard_id', $yardId)
                ->where('date', $date)
                ->whereHas('order', fn($q) => $q->where('status', 1))
                ->pluck('time')
                ->toArray();

            $timesForSelectedDate = $times->filter(fn($time) => !in_array($time->time, $bookedTimes));
        }

        return view('admin.orders.create', compact('yards', 'timesForSelectedDate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'yard_id' => 'required|exists:yards,yard_id',
            'date' => 'required|date',
            'time' => 'required|string',
            'price' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'status' => 0,
            'date' => now(),
            'user_id' => auth()->id(),
        ]);

        $order->orderDetails()->create([
            'yard_id' => $request->yard_id,
            'date' => $request->date,
            'time' => $request->time,
            'price' => $request->price,
            'notes' => $request->notes,
        ]);

        return redirect()->route('quan-ly-don-dat-san')->with('success', 'ThĂªm Ä‘Æ¡n Ä‘áº·t sĂ¢n má»›i thĂ nh cĂ´ng!');
    }

    public function edit($order_id)
    {
        $order = Order::with('orderDetails.yard')->findOrFail($order_id);
        $totalPrice = $order->orderDetails->sum('price');
        return view('admin.orders.update', compact('order', 'totalPrice'));
    }

    public function update(Request $request, $order_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $order = Order::findOrFail($order_id);

        $order->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        return redirect()->route('cap-nhat-don-dat-san', $order_id)
            ->with('success', 'Cáº­p nháº­t thĂ´ng tin Ä‘Æ¡n hĂ ng thĂ nh cĂ´ng!');
    }

    public function updateStatus(Request $request, $order_id)
    {
        $request->validate([
            'status' => 'required|in:0,1,2',
        ]);

        $order = Order::findOrFail($order_id);
        $order->status = (int) $request->status;
        $order->save();

        return redirect()->route('quan-ly-don-dat-san')->with('success', 'ÄĂ£ cáº­p nháº­t tráº¡ng thĂ¡i Ä‘Æ¡n hĂ ng!');
    }

    public function delete($order_id)
    {
        $order = Order::find($order_id);
        if ($order) {
            $order->delete();
            return redirect()->route('quan-ly-don-dat-san')->with('success', 'XĂ³a Ä‘Æ¡n hĂ ng thĂ nh cĂ´ng.');
        }
        return redirect()->route('quan-ly-don-dat-san')->with('error', 'KhĂ´ng tĂ¬m tháº¥y Ä‘Æ¡n hĂ ng.');
    }
}
