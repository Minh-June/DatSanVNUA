<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductOrderDetail;
use App\Models\Product;

class ProductOrderDetailController extends Controller
{
    public function index($product_order_detail_id)
    {
        $editDetail = ProductOrderDetail::with('order', 'product.sizes')
            ->findOrFail($product_order_detail_id);

        // Lấy danh sách sản phẩm theo shop
        $products = Product::where('store_id', $editDetail->product->store_id)
            ->get();

        // Lấy order cha
        $order = $editDetail->order;

        return view('admin.productorders.update', compact('editDetail', 'products', 'order'));
    }

    public function getProductInfo($id)
    {
        $product = Product::with('sizes')->findOrFail($id);

        return response()->json([
            'product' => [
                'price' => $product->price
            ],
            'sizes' => $product->sizes
        ]);
    }

    public function update(Request $request, $product_order_detail_id)
    {
        // 1. Lấy chi tiết cũ
        $detail = ProductOrderDetail::findOrFail($product_order_detail_id);

        // 2. Tính thành tiền cũ
        $oldTotal = $detail->price * $detail->quantity;

        // 3. Cập nhật sản phẩm
        $detail->product_id = $request->product_id;

        // 4. Nếu sản phẩm có size
        if (!empty($request->product_size_id)) {
            $detail->product_size_id = $request->product_size_id;

            $size = \App\Models\ProductSize::findOrFail($request->product_size_id);
            $detail->price = $size->price;
        } else {
            $detail->product_size_id = null;

            $product = \App\Models\Product::findOrFail($request->product_id);
            $detail->price = $product->price;
        }

        // 5. Cập nhật số lượng
        $detail->quantity = $request->quantity;

        // 6. Thành tiền mới
        $newTotal = $detail->price * $detail->quantity;

        // 7. Tính chênh lệch
        $diff = $newTotal - $oldTotal;

        // 8. Lưu chi tiết
        $detail->save();

        // 9. Cập nhật tổng tiền của đơn
        $order = $detail->order;
        $order->total_price += $diff;
        $order->save();

        // 10. Tạo thông báo
        $message = "Cập nhật chi tiết đơn thành công.";

        if ($diff > 0) {
            $message .= " Tổng tiền tăng thêm " . number_format($diff, 0, ',', '.') . "đ.";
        } elseif ($diff < 0) {
            $message .= " Tổng tiền giảm " . number_format(abs($diff), 0, ',', '.') . "đ.";
        }

        return redirect()
            ->route('cap-nhat-chi-tiet-don-mua-hang', $product_order_detail_id)
            ->with('price_change_message', $message);
    }

    public function delete($product_order_detail_id)
    {
        $detail = ProductOrderDetail::findOrFail($product_order_detail_id);
        $detail->delete();

        return back()->with('success', 'Xóa chi tiết đơn thành công.');
    }
}
