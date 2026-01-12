@extends('layouts.auth')

@section('title', 'Đăng Ký')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

@php
    use Carbon\Carbon;
    $maxDate = Carbon::now()->subYears(13)->format('Y-m-d');      // Phải đủ 13 tuổi
    $minDate = Carbon::now()->subYears(100)->format('Y-m-d');     // Không quá 100 tuổi
@endphp

<div class="container-access-register" id="signUp">
    <h2 class="form-title">Đăng Ký</h2>

    <form method="post" action="{{ route('dang-ky') }}">
        @csrf            

        {{-- Họ và tên --}}
        <div class="input-group">
            <i class="fa-regular fa-user"></i>
            <input type="text" id="fullname" name="fullname" placeholder="Họ và tên" 
                   value="{{ old('fullname') }}" required>
        </div>
        @error('fullname')
            <div class="error">{{ $message }}</div>
        @enderror

        {{-- Giới tính --}}
        <div class="input-group">
            <i class="fa-solid fa-genderless"></i>
            <label class="input-group-select" for="gender">Giới tính:</label>
            <select class="login-time-select" id="gender" name="gender" required>
                <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Chọn</option>
                <option value="Nam" {{ old('gender')=='Nam'?'selected':'' }}>Nam</option>
                <option value="Nữ" {{ old('gender')=='Nữ'?'selected':'' }}>Nữ</option>
                <option value="Khác" {{ old('gender')=='Khác'?'selected':'' }}>Khác</option>
            </select>
        </div>
        @error('gender')
            <div class="error">{{ $message }}</div>
        @enderror

        {{-- Ngày sinh --}}
        <div class="input-group">
            <i class="fa-solid fa-calendar"></i>
            <label class="input-group-select" for="birthdate">Ngày sinh:</label>
            <input class="login-time-select" type="date" id="birthdate" name="birthdate"
                   min="{{ $minDate }}" max="{{ $maxDate }}" 
                   value="{{ old('birthdate') }}" required>
        </div>
        @error('birthdate')
            <div class="error">{{ $message }}</div>
        @enderror

        {{-- Số điện thoại --}}
        <div class="input-group">
            <i class="fa-solid fa-phone"></i>
            <input type="text" id="phonenb" name="phonenb" placeholder="Số điện thoại" 
                   value="{{ old('phonenb') }}" required>
        </div>
        @error('phonenb')
            <div class="error">{{ $message }}</div>
        @enderror

        {{-- Email --}}
        <div class="input-group">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Email" 
                   value="{{ old('email') }}" required>
        </div>
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror

        {{-- Username --}}
        <div class="input-group">
            <i class="fa-solid fa-user"></i>
            <input type="text" name="username" id="username" placeholder="Tên người dùng" 
                   value="{{ old('username') }}" required>
        </div>
        @error('username')
            <div class="error">{{ $message }}</div>
        @enderror

        {{-- Mật khẩu --}}
        <div class="input-group">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
            <i class="fa-regular fa-eye toggle-password" style="cursor: pointer; margin-left: -30px;"></i>
        </div>
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror

        <input type="submit" class="index-btn" value="Đăng Ký" name="btnDangky">
    </form>        

    <div class="links">
        <p>Bạn đã có tài khoản ?</p>
        <a href="{{ route('dang-nhap') }}"><button id="signUpButton">Đăng Nhập</button></a>
    </div>
</div>

{{-- Toggle password --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.toggle-password');
    const password = document.getElementById('password');
    if(toggle){
        toggle.addEventListener('click', function(){
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            toggle.classList.toggle('fa-eye-slash');
        });
    }
});
</script>

@endsection
