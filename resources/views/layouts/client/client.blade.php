<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đặt lịch sân thể thao - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-free-6.5.2/css/all.min.css') }}">
</head>
<body>
    <div id="main">

        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="{{ route('trang-chu') }}" target="_top">Đặt lịch sân thể thao</a>
            
            <div class="header-login">
                <i class="avatar fa-solid fa-user-tie"></i>
                @if (Auth::check())
                    <a class="signup-btn" href="{{ route('thong-tin-tai-khoan') }}" target="_self">
                        {{ Auth::user()->username }}
                    </a>
                @else
                    <a class="signup-btn" href="{{ route('dang-nhap') }}" target="_self">Đăng Nhập</a>
                @endif
            </div>
        </div>
        <!-- End: Header -->

        @yield('content') <!-- Nơi để nội dung của các trang khác được chèn vào -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Powered by MJ</p>
        </div>
        <!-- End: Footer -->

    </div>
</body>
</html>
