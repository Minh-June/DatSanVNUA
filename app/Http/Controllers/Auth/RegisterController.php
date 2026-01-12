<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        // Mặc định là khách (role = 1)
        $role = 1;
        $managerId = null;

        // Nếu người đang đăng nhập là admin hoặc chủ thầu
        if (Auth::check() && in_array(Auth::user()->role, [0, 2])) {
            $role = 3; // Nhân viên
            $managerId = Auth::id(); // Gán manager_id là user_id của người tạo
        }

        // Tạo người dùng mới
        User::create([
            'username'   => $validated['username'],
            'password'   => Hash::make($validated['password']),
            'role'       => $role,
            'fullname'   => $validated['fullname'],
            'gender'     => $validated['gender'],
            'birthdate'  => $validated['birthdate'],
            'phonenb'    => $validated['phonenb'],
            'email'      => $validated['email'],
            'manager_id' => $managerId,
        ]);

        // Nếu là admin hoặc chủ thầu → quay lại trang quản lý người dùng
        if (Auth::check() && in_array(Auth::user()->role, [0, 2])) {
            return redirect()->route('quan-ly-nguoi-dung')->with('success', 'Thêm nhân viên thành công!');
        }

        // Nếu người tự đăng ký (không đăng nhập)
        return redirect()->route('dang-nhap')->with('success', 'Đăng ký tài khoản thành công!');
    }
}
