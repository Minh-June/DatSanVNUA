<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\Yard;
use App\Models\Type;
use App\Models\Time;
use Carbon\Carbon;

class OrderDetailController extends Controller
{
    public function index(Request $request, $order_detail_id)
    {
        $currentUser = auth()->user();
        $editDetail = OrderDetail::with('yard.type', 'order')->findOrFail($order_detail_id);
        $order = Order::with('orderDetails.yard.type')->findOrFail($editDetail->order_id);

        $types = Type::all();
        $selectedType = $request->input('type_id', $editDetail->yard->type_id ?? null);

        $yards = Yard::where('user_id', $currentUser->user_id)
            ->when($selectedType, fn($q) => $q->where('type_id', $selectedType))
            ->get();

        $selectedYard = $request->input('yard_id', $editDetail->yard_id);
        $selectedDate = $request->input('date', $editDetail->date);

        $timesForSelectedDate = collect();

        if ($selectedYard && $selectedDate) {
            $timesForSelectedDate = $this->getAvailableTimes($selectedYard, $selectedDate);
        }

        $totalPrice = $order->orderDetails->sum('price');

        return view('admin.orders.update', compact(
            'order', 'editDetail', 'types', 'yards',
            'selectedType', 'selectedYard', 'selectedDate', 'timesForSelectedDate',
            'totalPrice', 'currentUser'
        ));
    }

    public function getByType($type_id)
    {
        $currentUser = auth()->user();

        $yards = Yard::where('type_id', $type_id)
            ->where('user_id', $currentUser->user_id)
            ->get(['yard_id', 'name']);

        return response()->json($yards);
    }

    public function getTimesByYard($yard_id, $date)
    {
        $dayOfWeek = date('w', strtotime($date)); // 0=CN, 1=T2 ... 6=T7

        // Lấy tất cả khung giờ kinh điển (is_classic = 1) của sân
        $allTimes = Time::where('yard_id', $yard_id)
            ->where('status', 0)       // hiển thị
            ->where('is_classic', 1)   // chỉ khung giờ kinh điển
            ->get()
            ->map(function($t) use ($dayOfWeek) {
                return [
                    'time' => Carbon::parse($t->start)->format('H:i') . ' - ' . Carbon::parse($t->end)->format('H:i'),
                    'price' => ($dayOfWeek == 0 || $dayOfWeek == 6) ? $t->price_weekend : $t->price_weekday
                ];
            });

        // Lấy các khung giờ đã được đặt từ OrderDetail join với Order
        $bookedTimes = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->where('order_details.yard_id', $yard_id)
            ->where('order_details.date', $date)
            ->whereIn('orders.status', [1,3]) // ẩn các đơn đã xác nhận hoặc đặt cọc
            ->pluck('order_details.time')
            ->toArray();

        // Lọc bỏ các khung giờ đã đặt
        $times = collect($allTimes)
            ->filter(fn($t) => !in_array($t['time'], $bookedTimes))
            ->values();

        return response()->json($times);
    }

    // Hàm private dùng chung để lấy khung giờ khả dụng
    private function getAvailableTimes($yard_id, $date)
    {
        $dayOfWeek = date('w', strtotime($date));

        // Lấy tất cả khung giờ gợi ý (is_classic = 0) và hiển thị (status = 0)
        $allTimes = Time::where('yard_id', $yard_id)
            ->where('status', 0)
            ->where('is_classic', 0)
            ->get()
            ->map(fn($t) => [
                'time' => Carbon::parse($t->start)->format('H:i') . ' - ' . Carbon::parse($t->end)->format('H:i'),
                'price' => ($dayOfWeek == 0 || $dayOfWeek == 6) ? $t->price_weekend : $t->price_weekday
            ]);

        // Lấy các khung giờ đã bị đặt / đơn toàn kinh điển
        $bookedTimes = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.order_id')
            ->where('order_details.yard_id', $yard_id)
            ->where('order_details.date', $date)
            ->where(function($q){
                $q->whereIn('orders.status', [1,3])   // đã xác nhận hoặc đặt cọc
                ->orWhere('orders.auto_confirm', 1); // đơn toàn kinh điển
            })
            ->pluck('order_details.time')
            ->toArray();

        // Lọc bỏ các khung giờ đã đặt
        return collect($allTimes)
            ->filter(fn($t) => !in_array($t['time'], $bookedTimes))
            ->values();
    }

    public function update(Request $request, $order_detail_id)
    {
        $request->validate([
            'yard_id'=>'required|exists:yards,yard_id',
            'date'=>'required|date',
            'time'=>'required|string',
            'price'=>'required|numeric',
            'notes'=>'nullable|string',
        ]);

        $detail = OrderDetail::findOrFail($order_detail_id);
        $order = $detail->order;
        $oldTotal = $order->orderDetails->sum('price');

        $detail->update($request->only(['yard_id','date','time','price','notes']));

        $diff = $order->refresh()->orderDetails->sum('price') - $oldTotal;

        $message = "Cập nhật chi tiết đơn thành công.";
        if($diff>0) $message .= " Tổng tiền tăng thêm ".number_format($diff,0,',','.')."đ.";
        elseif($diff<0) $message .= " Tổng tiền giảm ".number_format(abs($diff),0,',','.')."đ.";

        return redirect()->route('cap-nhat-chi-tiet-don', $order_detail_id)
                         ->with('price_change_message',$message);
    }

    public function delete($order_detail_id)
    {
        $detail = OrderDetail::findOrFail($order_detail_id);
        $order_id = $detail->order_id;
        $detail->delete();

        $remainingDetails = OrderDetail::where('order_id',$order_id)->count();
        if($remainingDetails===0){
            return redirect()->route('quan-ly-don-dat-san')->with('success','Đã xóa hết chi tiết đơn.');
        }

        return redirect()->route('cap-nhat-chi-tiet-don',$order_id)->with('success','Đã xóa chi tiết đơn thành công!');
    }
}