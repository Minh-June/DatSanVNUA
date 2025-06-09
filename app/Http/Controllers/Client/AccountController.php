<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\AccountRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $ordersQuery = $user->orders()->with('orderDetails.yard');

        if ($request->has('date') && $request->date != '') {
            $date = $request->date;
            $ordersQuery->whereDate('date', $date);
        }

        $orders = $ordersQuery->get();

        // Nhóm chi tiết orderDetails theo yard_id và date
        foreach ($orders as $order) {
            $order->groupedDetails = $order->orderDetails->groupBy(function ($detail) {
                return $detail->yard_id . '_' . $detail->date;
            });
        }

        return view('client.account.index', compact('orders'));
    }

    // Hiển thị form cập nhật thông tin cá nhân
    public function editInfor()
    {
        $user = Auth::user(); // Lấy thông tin người dùng hiện tại
        return view('client.account.infor', compact('user'));
    }

    // Cập nhật thông tin cá nhân
    public function updateInfor(AccountRequest $request)
    {
        $user = Auth::user(); // Lấy thông tin người dùng hiện tại

        $user->fullname = $request->fullname;
        $user->gender = $request->gender;
        $user->birthdate = $request->birthdate;
        $user->phonenb = $request->phonenb;
        $user->email = $request->email;

        $user->save();

        return redirect()->route('thong-tin-ca-nhan')->with('success', 'Cập nhật thông tin cá nhân thành công.');
    }

    // Hiển thị form thay đổi mật khẩu
    public function editPassword()
    {
        return view('client.account.password');
    }

    // Cập nhật mật khẩu
    public function updatePassword(Request $request)
    {
        $user = Auth::user(); // Lấy thông tin người dùng hiện tại

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->matkhau_hientai, $user->password)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng.');
        }

        // Kiểm tra mật khẩu mới và xác nhận mật khẩu mới
        if ($request->matkhau_moi !== $request->xacnhan_matkhau) {
            return back()->with('error', 'Mật khẩu mới và xác nhận mật khẩu không khớp.');
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->matkhau_moi);
        $user->save();

        return redirect()->route('thay-doi-mat-khau')->with('success', 'Cập nhật mật khẩu thành công.');
    }

    public function delete(Request $request)
    {
        $user = Auth::user();

        // Kiểm tra mật khẩu
        if (!Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Mật khẩu không đúng. Không thể xóa tài khoản.');
        }

        $user->delete();
        Auth::logout();

        return redirect()->route('dang-nhap')->with('success', 'Tài khoản đã được xóa thành công.');
    }

}
