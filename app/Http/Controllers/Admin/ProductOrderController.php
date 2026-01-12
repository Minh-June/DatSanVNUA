<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductOrder;

class ProductOrderController extends Controller
{
    public function index(Request $request)
    {
        $currentUser = auth()->user();

        // Lấy query cơ bản
        $query = ProductOrder::with(['orderDetails.product.store']);

        // Lọc theo ngày: nếu có request thì dùng ngày đó, nếu không thì mặc định là hôm nay
        $selectedDate = $request->filled('selected_date') ? $request->selected_date : now()->toDateString();
        $query->whereDate('date', $selectedDate);

        // Lấy tất cả đơn, sau đó lọc theo shop/manager
        $orders = $query->get()->filter(function ($order) use ($currentUser) {
            return $order->orderDetails->contains(function ($detail) use ($currentUser) {
                if (!$detail->product || !$detail->product->store) return false;

                $store = $detail->product->store;
                return $store->user_id == $currentUser->user_id
                    || ($currentUser->role == 3 && $store->user_id == $currentUser->manager_id);
            });
        });

        // Tính tổng tiền cho mỗi đơn (chỉ tính sản phẩm của shop/manager hiện tại)
        foreach ($orders as $order) {
            $order->shop_total_price = $order->orderDetails->filter(function ($detail) use ($currentUser) {
                if (!$detail->product || !$detail->product->store) return false;

                $store = $detail->product->store;
                return $store->user_id == $currentUser->user_id
                    || ($currentUser->role == 3 && $store->user_id == $currentUser->manager_id);
            })->sum(function ($detail) {
                return $detail->price * $detail->quantity;
            });
        }

        return view('admin.productorders.index', compact('orders', 'selectedDate'));
    }

    public function updateStatus(Request $request, $product_order_id)
    {
        $order = ProductOrder::with('orderDetails')->findOrFail($product_order_id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        $order->status = $newStatus;
        $order->save();

        // Xử lý kho
        foreach ($order->orderDetails as $detail) {
            $product = $detail->product;
            $size = $detail->size; // nếu có size

            // Trường hợp trừ kho
            if (in_array($newStatus, [1,3]) && !in_array($oldStatus, [1,3])) {
                if ($size) {
                    $size->quantity -= $detail->quantity;
                    $size->save();
                } else {
                    $product->quantity -= $detail->quantity;
                    $product->save();
                }
            }

            // Trường hợp cộng kho
            if (in_array($newStatus, [0,2]) && in_array($oldStatus, [1,3])) {
                if ($size) {
                    $size->quantity += $detail->quantity;
                    $size->save();
                } else {
                    $product->quantity += $detail->quantity;
                    $product->save();
                }
            }
        }

        return redirect()->route('quan-ly-don-mua-hang')->with('success', 'Cập nhật trạng thái thành công.');
    }

    public function edit($product_order_id)
    {
        $order = ProductOrder::with('orderDetails.product.sizes', 'orderDetails.size')->findOrFail($product_order_id);

        return view('admin.productorders.update', compact('order'));
    }


    // Xóa đơn
    public function delete($product_order_id)
    {
        $order = ProductOrder::findOrFail($product_order_id);
        $order->orderDetails()->delete();
        $order->delete();

        return redirect()->route('quan-ly-don-mua-hang')->with('success', 'Xóa đơn mua hàng thành công.');
    }

}
