@extends('layouts.auth')

@section('title', 'Đăng Ký')

@section('content')
    <!-- Hiển thị thông báo thành công -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <!-- Hiển thị thông báo lỗi -->
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <div class="container-access-register" id="signUp">
        <h2 class="form-title">Đăng Ký</h2>

        @if ($errors->any())
            <div class="notice" role="alert">
                <h4>Đã có lỗi xảy ra !</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="post" action="{{ route('dang-ky') }}">
            @csrf            
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <input type="text" id="fullname" name="fullname" placeholder="Họ và tên" required>
                <label class="label-access" for="fullname"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-genderless"></i>
                <label class="input-group-select" for="gender">Giới tính:</label>
                <select class="login-time-select" id="gender" name="gender" required>
                    <option value="" disabled selected>Chọn</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-calendar"></i>
                <label class="input-group-select" for="birthdate">Ngày sinh:</label>
                <input class="login-time-select" type="date" id="birthdate" name="birthdate" required>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-phone"></i>
                <input type="text" id="phonenb" name="phonenb" placeholder="Số điện thoại" required>
                <label class="label-access" for="phonenb"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <label class="label-access" for="email"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" id="username" placeholder="Tên người dùng" required>
                <label class="label-access" for="username"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
                <label class="label-access" for="password"></label>
            </div>
        
            <input type="submit" class="index-btn" value="Đăng Ký" name="btnDangky">
        </form>        

        <div class="links">
            <p>Bạn đã có tài khoản?</p>
            <a href="{{ route('dang-nhap') }}"><button id="signUpButton">Đăng Nhập</button></a>
        </div>

    </div>
@endsection
