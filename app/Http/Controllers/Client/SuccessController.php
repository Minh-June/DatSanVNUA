<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Yard;
use App\Models\User;
use App\Models\Type;

class SuccessController extends Controller
{
    public function index($user_id = null)
    {
        $currentRoute = request()->route()->getName();

        if ($currentRoute === 'gio-hang') {
            // Hiển thị giỏ hàng
            $buys = session('buys', []);
            $totalItems = array_sum(array_column($buys, 'quantity'));
            $totalPrice = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $buys));

            return view('client.cart', [
                'buys' => $buys,
                'totalItems' => $totalItems,
                'totalPrice' => $totalPrice,
            ]);
        }

        // Ngược lại, hiển thị xác nhận đặt sân
        $orders = session('orders', []);
        $types = Type::all();

        $owners = collect();

        if ($user_id) {
            $user = User::find($user_id);
            $owners->push($user?->fullname ?? 'Minh');
        } elseif (!empty($orders)) {
            $yardIds = collect($orders)->pluck('yard_id')->unique();
            $yards = Yard::with('user')->whereIn('yard_id', $yardIds)->get();
            foreach ($yards as $yard) {
                $owners->push($yard->user?->fullname ?? 'Minh');
            }
        }

        return view('client.success', compact('orders', 'types', 'owners'));
    }

    public function delete(Request $request)
    {
        // Lấy danh sách đơn hàng từ session
        $orders = session('orders', []);
        
        // Lấy index của đơn hàng cần xóa
        $index = $request->input('index');
        
        // Kiểm tra nếu tồn tại đơn hàng tại index này
        if (isset($orders[$index])) {
            // Xóa đơn hàng khỏi session
            unset($orders[$index]);
            
            // Cập nhật lại session với danh sách đơn hàng đã xóa
            session(['orders' => array_values($orders)]); // array_values() để reset lại chỉ số mảng
        }

        // Quay lại trang danh sách đơn hàng sau khi xóa
        return redirect()->route('xac-nhan-dat-san');
    }
}
