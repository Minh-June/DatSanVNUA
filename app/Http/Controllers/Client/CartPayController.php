<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductOrder;
use App\Models\ProductOrderDetail;
use App\Http\Requests\Client\CartPayNowRequest;

class CartPayController extends Controller
{
    // Trang thanh toán tổng giỏ hàng (chọn cửa hàng)
    public function index()
    {
        $buys = session('buys', []);

        if (empty($buys)) {
            return redirect()->route('trang-chu')->with('empty_cart', true);
        }

        // Chuẩn hóa dữ liệu
        $buys = array_map(function ($item) {
            $item['store_id'] = isset($item['store_id']) ? (int)$item['store_id'] : 0;
            $item['product_id'] = (int)($item['product_id'] ?? 0);
            $item['price'] = (int)($item['price'] ?? 0);
            $item['quantity'] = (int)($item['quantity'] ?? 1);
            $item['product_size_id'] = $item['product_size_id'] ?? null;
            return $item;
        }, $buys);

        // Sắp xếp theo store_id, product_id
        usort($buys, fn($a, $b) => [$a['store_id'], $a['product_id']] <=> [$b['store_id'], $b['product_id']]);

        // Tính tổng tiền toàn giỏ
        $totalPrice = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $buys));

        $user = Auth::user();

        // Danh sách store_id duy nhất
        $stores = array_unique(array_column($buys, 'store_id'));

        return view('client.cartpay', compact('buys', 'totalPrice', 'user', 'stores'));
    }

    // Trang thanh toán online cho từng cửa hàng
    public function payNow($owner_id)
    {
        $buys = session('buys', []);
        if (empty($buys)) {
            return redirect()->route('trang-chu')->with('error', 'Giỏ hàng trống.');
        }

        // Lọc sản phẩm của cửa hàng được chọn
        $storeItems = array_filter($buys, fn($item) => ($item['store_id'] ?? 0) == $owner_id);

        if (empty($storeItems)) {
            return redirect()->route('trang-chu')->with('error', 'Không tìm thấy sản phẩm cho cửa hàng này.');
        }

        $store = \App\Models\Store::find($owner_id);
        if (!$store) {
            return redirect()->route('trang-chu')->with('error', 'Không tìm thấy cửa hàng.');
        }

        $ownerModel = $store->user;
        $user = Auth::user();

        return view('client.cartpaynow', compact('store', 'storeItems', 'user', 'ownerModel'));
    }

    // Thanh toán offline (COD)
    public function storeOffline(Request $request)
    {
        $user = Auth::user();
        $buys = session('buys', []);

        if (empty($buys)) {
            return redirect()->route('trang-chu')->with('error', 'Giỏ hàng trống.');
        }

        foreach (array_unique(array_column($buys, 'store_id')) as $storeId) {
            $storeItems = array_filter($buys, fn($item) => $item['store_id'] == $storeId);
            $totalPrice = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $storeItems));

            // Tạo đơn hàng
            $order = ProductOrder::create([
                'user_id'     => $user->user_id,
                'store_id'    => $storeId,
                'total_price' => $totalPrice,
                'status'      => 0, // chờ xác nhận
                'image'       => null,
                'address'     => $request->input('address', $user->address),
                'name'        => $request->input('fullname', $user->fullname),
                'phonenb'     => $request->input('phonenb', $user->phonenb),
                'email'       => $request->input('email', $user->email),
                'date'        => now(),
                'notes'       => $request->input('notes'),
            ]);

            // Lưu chi tiết sản phẩm
            foreach ($storeItems as $item) {
                ProductOrderDetail::create([
                    'product_order_id' => $order->product_order_id,
                    'product_id'       => $item['product_id'],
                    'quantity'         => $item['quantity'],
                    'price'            => $item['price'],
                    'product_size_id'  => $item['product_size_id'] ?? null,
                ]);
            }
        }

        session()->forget('buys');

        return redirect()->route('trang-chu')->with('success', 'Đặt mua hàng thành công!');
    }

    // Thanh toán online (upload hình ảnh chuyển khoản)
    public function storeOnline(CartPayNowRequest $request)
    {
        $user = Auth::user();
        $buys = session('buys', []);
        $storeId = $request->input('store_id');

        if (empty($buys)) {
            return redirect()->route('trang-chu')->with('error', 'Giỏ hàng trống.');
        }

        $products = $request->input('products', []);

        $totalPrice = array_reduce($products, fn($carry, $item) => $carry + $item['price'] * $item['quantity'], 0);

        $order = ProductOrder::create([
            'user_id'     => $user->user_id,
            'store_id'    => $storeId,
            'total_price' => $totalPrice,
            'status'      => 0,
            'address'     => $request->input('address'),
            'name'        => $request->input('fullname'),
            'phonenb'     => $request->input('phonenb'),
            'email'       => $request->input('email'),
            'notes'       => $request->input('notes', ''),
            'image'       => null,
            'date'        => now(),
        ]);

        // Lưu chi tiết sản phẩm
        foreach ($products as $product) {
            ProductOrderDetail::create([
                'product_order_id' => $order->product_order_id,
                'product_id'       => $product['product_id'],
                'quantity'         => $product['quantity'],
                'price'            => $product['price'],
                'product_size_id'  => $product['product_size_id'] ?? null,
            ]);
        }

        // Lưu ảnh chuyển khoản
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $img) {
                $images[] = $img->store('orders/' . $order->product_order_id, 'public');
            }
            $order->image = json_encode($images);
            $order->save();
        }

        // Xóa sản phẩm của cửa hàng này khỏi session
        session(['buys' => array_filter($buys, fn($item) => $item['store_id'] != $storeId)]);

        return redirect()->route('thanh-toan-gio-hang')
                         ->with('success', 'Thanh toán thành công! Vui lòng thanh toán các đơn còn lại.');
    }
}
