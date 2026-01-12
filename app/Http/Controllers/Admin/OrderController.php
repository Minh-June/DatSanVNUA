<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\User;
use App\Models\Yard;
use App\Models\Time;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $selectedDate = $request->input('selected_date', now()->toDateString());

        // --- ADMIN ---
        if ($user->role == 0) {
            $adminId = $user->user_id;

            $orders = Order::with('orderDetails.yard', 'user')
                            ->when($selectedDate, fn($q) => $q->whereDate('date', $selectedDate))
                            ->get();

            // 1️⃣ Đơn có sân do admin quản lý
            $managedOrders = $orders->filter(fn($order) => 
                $order->orderDetails->contains(fn($d) => $d->yard && $d->yard->user_id == $adminId)
            )->sortBy(fn($order) => strtotime($order->date))
            ->values();

            // 2️⃣ Đơn do admin đặt
            $adminPlacedOrders = $orders->filter(fn($order) => $order->user_id == $adminId)
                                        ->sortByDesc(fn($order) => strtotime($order->date))
                                        ->values();

            // Gộp 2 nhóm
            $orders = $managedOrders->merge($adminPlacedOrders)->values();
        }

        // --- Chủ thầu ---
        elseif ($user->role == 2) {
            $yardIds = Yard::where('user_id', $user->user_id)->pluck('yard_id');
            $staffIds = User::where('manager_id', $user->user_id)->where('role',3)->pluck('user_id');

            $orders = Order::where(function($q) use ($yardIds,$staffIds,$user){
                                $q->whereHas('orderDetails', fn($qu)=> $qu->whereIn('yard_id', $yardIds))
                                ->orWhereIn('user_id',$staffIds)
                                ->orWhere('user_id',$user->user_id);
                            })
                            ->with('orderDetails.yard','user')
                            ->when($selectedDate, fn($q) => $q->whereDate('date', $selectedDate))
                            ->get();

            $orders = $orders->sort(function($a,$b) use ($user){
                $aOwned = $a->orderDetails->contains(fn($d)=> $d->yard && $d->yard->user_id==$user->user_id);
                $bOwned = $b->orderDetails->contains(fn($d)=> $d->yard && $d->yard->user_id==$user->user_id);

                if($aOwned != $bOwned) return $aOwned?-1:1;

                if($a->status==$b->status){
                    if($a->status==0) return strtotime($a->created_at)<=>strtotime($b->created_at);
                    else return strtotime($b->created_at)<=>strtotime($a->created_at);
                }

                $statusOrder=[0=>0,1=>1,2=>2,3=>3];
                return $statusOrder[$a->status]<=>$statusOrder[$b->status];
            })->values();
        }

        // --- Nhân viên ---
        elseif ($user->role == 3) {
            $managerId = $user->manager_id;
            $yardIds = Yard::where('user_id',$managerId)->pluck('yard_id');

            $orders = Order::whereHas('orderDetails', fn($q)=> $q->whereIn('yard_id',$yardIds))
                            ->with('orderDetails.yard','user')
                            ->when($selectedDate, fn($q)=> $q->whereDate('date', $selectedDate))
                            ->get();

            $orders = $orders->sort(function($a,$b){
                if($a->status==$b->status){
                    if($a->status==0) return strtotime($a->created_at)<=>strtotime($b->created_at);
                    else return strtotime($b->created_at)<=>strtotime($a->created_at);
                }
                $statusOrder=[0=>0,1=>1,2=>2,3=>3];
                return $statusOrder[$a->status]<=>$statusOrder[$b->status];
            })->values();
        }

        // --- Khách hàng ---
        else {
            $orders = Order::where('user_id',$user->user_id)
                            ->with('orderDetails.yard')
                            ->when($selectedDate, fn($q)=> $q->whereDate('date', $selectedDate))
                            ->orderByDesc('created_at')
                            ->get();
        }

        // Nhóm chi tiết đơn theo sân
        $orders->transform(function($order){
            $grouped = $order->orderDetails->groupBy('yard_id');
            $order->groupedDetails = $grouped;
            $order->rowspan = $grouped->count();
            return $order;
        });

        return view('admin.orders.index', compact('orders'));
    }

    public function edit($order_id)
    {
        $order = Order::with('orderDetails.yard')->findOrFail($order_id);
        $totalPrice = $order->orderDetails->sum('price');
        return view('admin.orders.update', compact('order', 'totalPrice'));
    }

    public function updateStatus(Request $request, $order_id)
    {
        $order = Order::with('orderDetails')->findOrFail($order_id);

        // Lấy ảnh thanh toán để kiểm tra
        $images = json_decode($order->image);

        // Validate status: chỉ cho phép 3 nếu có ảnh
        $allowedStatuses = [0,1,2];
        if($images && count($images) > 0) {
            $allowedStatuses[] = 3;
        }

        $request->validate([
            'status' => 'required|in:' . implode(',', $allowedStatuses),
        ]);

        $newStatus = (int) $request->status;

        // Cập nhật trạng thái mới cho đơn hiện tại
        $order->status = $newStatus;
        $order->save();

        // Nếu admin chọn "Xác nhận" => tự động hủy các đơn khác trùng sân, ngày, khung giờ
        if ($newStatus === 1) {
            foreach ($order->orderDetails as $detail) {
                $conflictOrders = \App\Models\OrderDetail::where('yard_id', $detail->yard_id)
                    ->where('date', $detail->date)
                    ->where('time', $detail->time)
                    ->where('order_id', '!=', $order_id)
                    ->pluck('order_id');

                if ($conflictOrders->count()) {
                    \App\Models\Order::whereIn('order_id', $conflictOrders)
                        ->where('status', '!=', 2) // chỉ cập nhật nếu chưa bị hủy
                        ->update(['status' => 2]);
                }
            }
        }

        return redirect()->route('quan-ly-don-dat-san')
            ->with('success', 'Cập nhật trạng thái đơn đặt sân thành công !');
    }

    public function delete($order_id)
    {
        $order = Order::find($order_id);
        if ($order) {
            $order->delete();
            return redirect()->route('quan-ly-don-dat-san')->with('success', 'Xóa đơn đơn đặt sân thành công !');
        }
        return redirect()->route('quan-ly-don-dat-san')->with('error', 'Không tìm thấy đơn đơn đặt sân.');
    }
}
