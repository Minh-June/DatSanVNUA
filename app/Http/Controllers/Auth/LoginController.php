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
        $request->authenticate();

        $user = Auth::user();

        // Điều hướng người dùng theo vai trò
        if ($user->role == 1) {
            return redirect()->route('trang-chu');
        } else {
            return redirect()->route('thong-ke-bao-cao');
        }
    }  

    public function logout(Request $request)
    {
        Auth::logout(); // Đăng xuất người dùng

        return redirect()->route('view');
    }
}
