<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Yard;
use App\Models\Time;
use Illuminate\Support\Facades\DB;

class OrderDetailController extends Controller
{
    public function index(Request $request, $order_detail_id)
    {
        $editDetail = OrderDetail::with('yard', 'order')->findOrFail($order_detail_id);
        $order = Order::with('orderDetails.yard')->find($editDetail->order_id);
        $yards = Yard::all();

        $selectedYard = $request->input('yard_id', $editDetail->yard_id);
        $selectedDate = $request->input('date', $editDetail->date);

        $timesForSelectedDate = collect();

        if ($selectedYard && $selectedDate) {
            $bookedTimes = DB::table('order_details')
                ->join('orders', 'order_details.order_id', '=', 'orders.order_id')
                ->where('orders.status', 1)
                ->where('order_details.date', $selectedDate)
                ->where('order_details.yard_id', $selectedYard)
                ->where('order_details.order_detail_id', '!=', $order_detail_id) // exclude current detail
                ->pluck('order_details.time')
                ->toArray();

            $timesForSelectedDate = Time::where('yard_id', $selectedYard)
                ->whereDate('date', $selectedDate)
                ->whereNotIn('time', $bookedTimes)
                ->get();
        }

        $totalPrice = $order ? $order->orderDetails->sum('price') : 0;

        return view('admin.orders.update', compact('order', 'editDetail', 'timesForSelectedDate', 'yards', 'totalPrice'));
    }

    public function update(Request $request, $order_detail_id)
    {
        $request->validate([
            'yard_id' => 'required|exists:yards,yard_id',
            'date' => 'required|date',
            'time' => 'required|string',
            'price' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $orderDetail = OrderDetail::findOrFail($order_detail_id);

        // Tá»•ng tiá»n cÅ© cá»§a Ä‘Æ¡n
        $order = $orderDetail->order;
        $oldTotal = $order->orderDetails->sum('price');

        // Cáº­p nháº­t chi tiáº¿t Ä‘Æ¡n
        $orderDetail->update([
            'yard_id' => $request->yard_id,
            'date' => $request->date,
            'time' => $request->time,
            'price' => $request->price,
            'notes' => $request->notes,
        ]);

        // TĂ­nh láº¡i tá»•ng tiá»n má»›i
        $order->refresh();
        $newTotal = $order->orderDetails->sum('price');

        $diff = $newTotal - $oldTotal;

        if ($diff > 0) {
            $message = "Cáº­p nháº­t chi tiáº¿t Ä‘Æ¡n thĂ nh cĂ´ng. Tá»•ng tiá»n tÄƒng thĂªm " . number_format($diff, 0, ',', '.') . " VND.";
        } elseif ($diff < 0) {
            $message = "Cáº­p nháº­t chi tiáº¿t Ä‘Æ¡n thĂ nh cĂ´ng. Tá»•ng tiá»n giáº£m " . number_format(abs($diff), 0, ',', '.') . " VND.";
        } else {
            $message = "Cáº­p nháº­t chi tiáº¿t Ä‘Æ¡n thĂ nh cĂ´ng. Tá»•ng tiá»n khĂ´ng thay Ä‘á»•i.";
        }

        return redirect()->route('cap-nhat-chi-tiet-don', $order_detail_id)
            ->with('price_change_message', $message);
}

    public function delete($order_detail_id)
    {
        $detail = OrderDetail::findOrFail($order_detail_id);
        $order_id = $detail->order_id;
        $detail->delete();

        return redirect()->route('cap-nhat-don-dat-san', $order_id)
            ->with('success', 'ÄĂ£ xĂ³a chi tiáº¿t Ä‘Æ¡n thĂ nh cĂ´ng.');
    }
}
