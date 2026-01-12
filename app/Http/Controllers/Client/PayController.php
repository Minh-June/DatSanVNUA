<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\PayNowRequest;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;

class PayController extends Controller
{
    public function index()
    {
        $orders = session('orders', []);
        if (empty($orders)) {
            return redirect()->route('trang-chu')
                ->with('error', 'Không có đơn đặt sân nào để thanh toán.');
        }

        // Nếu chưa có thời gian bắt đầu, set countdown
        if (!session()->has('payment_start_time')) {
            session(['payment_start_time' => now()]);
        }

        // Đảm bảo mỗi order có keys cần thiết
        foreach ($orders as &$order) {
            $order['times'] = $order['times'] ?? [];
            $order['price_per_slot'] = $order['price_per_slot'] ?? [];
            $order['is_classic_per_slot'] = $order['is_classic_per_slot'] ?? [];
        }
        unset($order);

        session(['orders' => $orders]); // cập nhật lại session

        return view('client.pay', [
            'orders' => $orders,
            'startTime' => session('payment_start_time'),
        ]);
    }

    public function storeOffline(Request $request)
    {
        $orders = session('orders', []);
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập trước khi thanh toán.');
        }

        if (empty($orders)) {
            return redirect()->back()->with('error', 'Không có đơn đặt sân nào để thanh toán.');
        }

        // Nhóm theo chủ sân
        $grouped = collect($orders)->groupBy('yard_owner_id');

        foreach ($grouped as $ownerId => $ownerOrders) {

            // Kiểm tra có ít nhất 1 sân không kinh điển
            $hasNonClassic = false;
            foreach ($ownerOrders as $item) {
                foreach ($item['is_classic'] as $val) {
                    if ($val) {
                        $hasNonClassic = true;
                        break 2;
                    }
                }
            }

            // Tạo order (bỏ notes)
            $order = new Order();
            $order->user_id = $userId;
            $order->name = $ownerOrders[0]['name'];
            $order->phone = $ownerOrders[0]['phone'];
            $order->date = now();
            $order->status = $hasNonClassic ? Order::STATUS_PENDING : Order::STATUS_CONFIRMED;
            $order->auto_confirm = $hasNonClassic ? 1 : 0;
            $order->image = 'Thanh toán trực tiếp';
            $order->save();

            // Lưu chi tiết từng sân (vẫn lưu notes ở đây)
            foreach ($ownerOrders as $item) {
                foreach ($item['times'] as $index => $time) {
                    OrderDetail::create([
                        'order_id'   => $order->order_id,
                        'yard_id'    => $item['yard_id'],
                        'type_id'    => $item['type_id'],
                        'date'       => $item['date'],
                        'time'       => $time,
                        'price'      => $item['price_per_slot'][$index] ?? 0,
                        'is_classic' => $item['is_classic'][$index] ?? 0,
                        'notes'      => $item['notes'] ?? '', // ghi chú ở đây
                    ]);
                }
            }
        }

        session()->forget('orders');

        $role = auth()->user()->role;
        if ($role != 1) {
            return redirect()->route('quan-ly-don-dat-san')->with('success', 'Bạn đã đặt sân thành công!');
        }

        return redirect()->route('trang-chu')->with('success', 'Đặt sân thành công, vui lòng chờ chủ sân xác nhận!');
    }

    public function storeOnline(PayNowRequest $request)
    {
        $orders = session('orders', []);
        $userId = auth()->id();
        $ownerId = $request->input('owner_id');

        if (!$userId) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập trước khi thanh toán.');
        }

        if (empty($ownerId)) {
            return redirect()->back()->with('error', 'Không xác định được chủ sân.');
        }

        $ownerOrders = collect($orders)->where('yard_owner_id', $ownerId)->values();
        if ($ownerOrders->isEmpty()) {
            return redirect()->back()->with('error', 'Không tìm thấy đơn phù hợp.');
        }

        // Xử lý ảnh
        $imagesInput = $request->file('images');
        $imagePaths = [];
        if (!empty($imagesInput)) {
            foreach ($imagesInput as $img) {
                $path = $img->storeAs('bills', uniqid() . '_' . $img->getClientOriginalName(), 'public');
                $imagePaths[] = $path;
            }
        }
        $imageValue = !empty($imagePaths) ? json_encode($imagePaths) : 'Thanh toán trực tiếp';

        // Kiểm tra có ít nhất 1 sân không kinh điển
        $hasNonClassic = false;
        foreach ($ownerOrders as $item) {
            foreach ($item['is_classic'] as $val) {
                if ($val) {
                    $hasNonClassic = true;
                    break 2;
                }
            }
        }

        // Tạo order (bỏ notes)
        $order = new Order();
        $order->user_id = $userId;
        $order->name = $ownerOrders[0]['name'];
        $order->phone = $ownerOrders[0]['phone'];
        $order->date = now();
        $order->status = $hasNonClassic ? Order::STATUS_PENDING : Order::STATUS_CONFIRMED;
        $order->auto_confirm = $hasNonClassic ? 1 : 0;
        $order->image = $imageValue;
        $order->save();

        // Lưu chi tiết từng sân (vẫn lưu notes ở đây)
        foreach ($ownerOrders as $item) {
            foreach ($item['times'] as $index => $time) {
                OrderDetail::create([
                    'order_id'   => $order->order_id,
                    'yard_id'    => $item['yard_id'],
                    'type_id'    => $item['type_id'],
                    'date'       => $item['date'],
                    'time'       => $time,
                    'price'      => $item['price_per_slot'][$index] ?? 0,
                    'is_classic' => $item['is_classic'][$index] ?? 0,
                    'notes'      => $item['notes'] ?? '', // ghi chú ở đây
                ]);
            }
        }

        // Cập nhật session: xóa các đơn vừa thanh toán
        $remainingOrders = collect($orders)
            ->reject(fn($o) => ($o['yard_owner_id'] ?? 0) == $ownerId)
            ->values()
            ->toArray();

        if (!empty($remainingOrders)) {
            session(['orders' => $remainingOrders]);
        } else {
            session()->forget('orders');
        }

        return !empty($remainingOrders)
            ? redirect()->route('thanh-toan')->with('success', 'Thanh toán thành công! Vui lòng thanh toán các đơn còn lại.')
            : redirect()->route('trang-chu')->with('success', 'Bạn đã đặt sân thành công!');
    }

    public function payNow($ownerId)
    {
        $orders = session('orders', []);
        $ownerOrders = collect($orders)->where('yard_owner_id', $ownerId)->values();

        if ($ownerOrders->isEmpty()) {
            return redirect()->route('thanh-toan')->with('error', 'Không tìm thấy đơn của chủ sân này.');
        }

        if (!session()->has('payment_start_time')) {
            session(['payment_start_time' => now()]);
        }

        return view('client.paynow', [
            'ownerOrders' => $ownerOrders,
            'startTime' => session('payment_start_time'),
        ]);
    }

    public function timeout(Request $request)
    {
        // Xóa session liên quan đến đơn
        session()->forget('orders');
        session()->forget('current_order_key');
        session()->forget('payment_start_time');

        // Nếu request từ submit offline để reset orderKey thì không redirect
        if ($request->has('reset_order_key')) {
            return response()->json(['status' => 'ok']);
        }

        // Chuyển về trang chủ
        return redirect()->route('trang-chu');
    }
}