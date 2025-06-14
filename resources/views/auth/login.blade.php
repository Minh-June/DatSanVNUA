@extends('layouts.auth')

@section('title', 'Đăng Nhập')

@section('content')
    <div class="container-access-login" id="signIn">
        <h2 class="form-title">Đăng Nhập</h2>

        <form method="post" action="{{ route('dang-nhap') }}">
            @csrf
            @method('post')

            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" id="username" placeholder="Tên người dùng" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
            </div>

            {{-- Hiển thị lỗi ngay trên nút đăng nhập --}}
            @if ($errors->any())
                <div class="notice">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <input type="submit" class="index-btn" value="Đăng Nhập">
        </form>

        <div class="links">
            <p>Bạn chưa có tài khoản?</p>
            <a href="{{ route('dang-ky') }}"><button id="signUpButton">Đăng Ký</button></a>
        </div>
    </div>
@endsection
