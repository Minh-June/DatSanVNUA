<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User; 

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $request->validated();

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
           
            $user = Auth::user();
            
            // Điều hướng người dùng theo vai trò
            if ($user->role == 1) {
                return redirect()->route('trang-chu'); // Điều hướng đến trang chủ
            } else {
                return redirect()->route('admin'); // Điều hướng đến trang quản trị
            }
        }
        
        // Đăng nhập thất bại
        return redirect()->route('dang-nhap')->withErrors([
            'message' => 'Tên người dùng hoặc mật khẩu không đúng.',
        ]);
            
    }   

    public function logout(Request $request)
    {
        Auth::logout(); // Đăng xuất người dùng

        return redirect()->route('view');
    }
}
