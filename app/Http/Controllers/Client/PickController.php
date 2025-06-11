<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Yard;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Time;
use App\Http\Requests\Client\PickRequest;

class PickController extends Controller
{
    public function index($yard_id, Request $request)
    {
        $yard = Yard::with('images')->find($yard_id);
        if (!$yard) {
            return redirect()->back()->with('error', 'SĂ¢n khĂ´ng tá»“n táº¡i');
        }

        $selected_date = $request->query('date', date('Y-m-d'));

        // 1. Láº¥y táº¥t cáº£ khung giá» sĂ¢n theo ngĂ y
        $times = Time::where('yard_id', $yard_id)
            ->where('date', $selected_date)
            ->get();

        // 2. Láº¥y cĂ¡c khung giá» Ä‘Ă£ Ä‘Æ°á»£c admin xĂ¡c nháº­n (status = 1) - disable cho táº¥t cáº£
        $adminBookedTimes = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->where('order_details.yard_id', $yard_id)
            ->where('order_details.date', $selected_date)
            ->where('orders.status', 1) // tráº¡ng thĂ¡i admin xĂ¡c nháº­n
            ->pluck('order_details.time')
            ->toArray();

        // 3. Láº¥y cĂ¡c khung giá» Ä‘Ă£ chá»n trong session cá»§a user hiá»‡n táº¡i (chá»‰ disable vá»›i chĂ­nh user nĂ y)
        $sessionBookedTimes = [];
        $orders = session('orders', []);
        $userId = auth()->id();

        foreach ($orders as $order) {
            // Chá»‰ láº¥y Ä‘Æ¡n cĂ¹ng user_id hiá»‡n táº¡i, cĂ¹ng sĂ¢n vĂ  cĂ¹ng ngĂ y
            if (($order['user_id'] ?? null) == $userId
                && $order['yard_id'] == $yard_id
                && $order['date'] == $selected_date) {
                $sessionBookedTimes = array_merge($sessionBookedTimes, $order['times']);
            }
        }
        $sessionBookedTimes = array_unique($sessionBookedTimes);

        $yard_name = $yard->name;
        $yard_image = $yard->images->first();

        $user = auth()->check() ? auth()->user() : null;

        return view('client.pick', compact(
            'yard',
            'yard_name',
            'yard_image',
            'times',
            'adminBookedTimes',
            'sessionBookedTimes',
            'selected_date',
            'userId',
            'yard_id',
            'user'
        ));
    }
    
    public function store(PickRequest $request)
    {
        $yard = Yard::findOrFail($request->input('yard_id'));

        $selected_times = $request->input('selected_times', []);
        if (empty($selected_times)) {
            return redirect()->back()->withErrors(['selected_times' => 'Vui lĂ²ng chá»n Ă­t nháº¥t má»™t khung giá».']);
        }

        $total_price = (int) $request->input('total_price');

        // Láº¥y máº£ng giĂ¡ tá»«ng slot tá»« input, dáº¡ng JSON string -> decode thĂ nh máº£ng sá»‘ nguyĂªn
        $price_per_slot = $request->input('price_per_slot', '[]');
        $price_per_slot_array = json_decode($price_per_slot, true);
        if (!is_array($price_per_slot_array) || count($price_per_slot_array) !== count($selected_times)) {
            return redirect()->back()->withErrors(['price_per_slot' => 'Dá»¯ liá»‡u giĂ¡ khung giá» khĂ´ng há»£p lá»‡.']);
        }

        $user = auth()->user();
        $created_at = now()->toDateTimeString();

        $order = [
            'user_id' => $user->user_id,
            'yard_id' => $yard->yard_id,
            'yard_name' => $yard->name,
            'name' => $user->fullname,
            'phone' => $user->phonenb,
            'times' => $selected_times,
            'date' => $request->input('date'),
            'price' => $total_price,
            'price_per_slot' => $price_per_slot_array, // lÆ°u máº£ng giĂ¡ tá»«ng slot
            'notes' => $request->input('notes'),
            'created_at' => $created_at,
        ];

        $orders = session('orders', []);
        $orders[] = $order;
        session(['orders' => $orders]);

        return redirect()->route('xac-nhan-dat-san');
    }

}
