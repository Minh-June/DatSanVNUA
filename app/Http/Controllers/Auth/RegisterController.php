<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        
        // Mã hóa mật khẩu bằng Hash
        $validated['password'] = Hash::make($validated['password']);
        
        // Bạn có thể gán vai trò ở đây nếu không được gửi từ form
        $validated['role'] = 1; 
        
        // Tạo người dùng mới
        $user = User::create($validated); // Sử dụng $validated để đảm bảo chỉ lưu dữ liệu đã xác thực.
        
        return redirect()->route('dang-nhap')->with('success', 'Đăng ký thành công!');
    }    
}
