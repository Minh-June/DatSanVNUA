@extends('layouts.auth')

@section('title', 'Đăng Nhập')

@section('content')
    <div class="container-access" id="signIn">
        <h1 class="form-title">Đăng Nhập</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('dang-nhap') }}">
            @csrf
            @method('post')
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="username" name="username" id="username" placeholder="Tên người dùng" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
            </div>
            <input type="submit" class="index-btn" value="Đăng Nhập">
        </form>

        <div class="links">
            <p>Bạn chưa có tài khoản?</p>
            <a href="{{ route('dang-ky') }}"><button id="signUpButton">Đăng Ký</button></a>
        </div>
    </div>
@endsection
