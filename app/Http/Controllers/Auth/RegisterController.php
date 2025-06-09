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
        // Xác thực dữ liệu đầu vào
        $validated = $request->validated();

        // Tạo người dùng mới vào bảng 'users'
        User::create([
            'username'  => $validated['username'],
            'password'  => Hash::make($validated['password']),
            'role'      => 1, // Mặc định là người dùng thường
            'fullname'  => $validated['fullname'],
            'gender'    => $validated['gender'],
            'birthdate' => $validated['birthdate'],
            'phonenb'   => $validated['phonenb'],
            'email'     => $validated['email'],
        ]);

        // Kiểm tra nếu user hiện tại là admin (role = 0)
        if (Auth::check() && Auth::user()->role == 0) {
            return redirect()->route('quan-ly-nguoi-dung')->with('success', 'Thêm người dùng thành công!');
        }

        // Nếu không phải admin thì redirect về đăng nhập
        return redirect()->route('dang-nhap')->with('success', 'Đăng ký thành công!');
    }
}
