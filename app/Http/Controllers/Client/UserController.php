<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Client\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Lấy user_id từ session
        $userId = session('user_id');

        // Lấy ngày được chọn từ request, nếu có
        $selectedDate = $request->input('selected_date');

        // Nếu có ngày được chọn, lọc đơn đặt sân theo ngày đó và sắp xếp theo ngày giảm dần
        if ($selectedDate) {
            $orders = Order::where('user_id', $userId)
                            ->whereDate('date', $selectedDate)
                            ->orderBy('date', 'desc')
                            ->get();
        } else {
            // Nếu không có ngày nào được chọn, hiển thị tất cả các order, sắp xếp theo ngày giảm dần
            $orders = Order::where('user_id', $userId)
                            ->orderBy('date', 'desc')
                            ->get();
        }

        // Trả về view và truyền dữ liệu orders và selectedDate
        return view('client.user.index', compact('orders', 'selectedDate'));
    }

    public function edit()
    {
        // Lấy thông tin người dùng từ session
        $userId = session('user_id');
        $user = User::find($userId);

        return view('client.user.infor', compact('user'));
    }

    public function update(UserRequest $request)
    {
        // Lấy user_id từ session
        $userId = session('user_id');

        // Cập nhật thông tin người dùng
        $user = User::find($userId);
        $user->fullname = $request->input('fullname');
        $user->gender = $request->input('gender');
        $user->birthdate = $request->input('birthdate');
        $user->phonenb = $request->input('phonenb');
        $user->email = $request->input('email');
        $user->save();

        return redirect()->route('thong-tin-ca-nhan')->with('success', 'Thông tin của bạn đã được cập nhật thành công !');
    }

    public function changePassword()
    {
        return view('client.user.password');
    }

    public function updatePassword(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'matkhau_hientai' => 'required',
            'matkhau_moi' => 'required|min:3',
            'xacnhan_matkhau' => 'required|same:matkhau_moi',
        ]);

        // Lấy user từ session
        $user = User::find(session('user_id'));

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->input('matkhau_hientai'), $user->password)) {
            return back()->withErrors(['matkhau_hientai' => 'Mật khẩu hiện tại không chính xác !']);
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->input('matkhau_moi'));
        $user->save();

        // Flash message
        return redirect()->route('thay-doi-mat-khau')->with('success', 'Mật khẩu của bạn đã được cập nhật thành công !');
    }

}


