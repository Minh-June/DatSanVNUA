<!DOCTYPE html>
<html lang="en">
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
                <form action="{{ route('dang-xuat') }}" method="post" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                    @csrf
                    <button type="submit" class="signup-btn">Đăng xuất</button>
                </form>
            </div>
        </div>
        <!-- End: Header -->

        <!-- Begin: Content -->
        <div id="content" class="admin-section">
            <div class="admin-content">
                <div class="admin">
                    <div class="admin-section-left">
                        <div class="header-section-left">
                            <i class="avatar fa-solid fa-user-tie"></i>
                            @if (Auth::check())
                                <a class="avatar-name" href="{{ route('thong-tin-tai-khoan') }}" target="_self">
                                    {{ Auth::user()->username }}
                                </a>
                            @else
                                <a class="avatar-name" href="{{ route('dang-nhap') }}" target="_self">Đăng Nhập</a>
                            @endif
                        </div>
                        
                        <div class="admin-manage">
                            <li>
                                <ul class="section-left">
                                    <li><a href="{{ route('thong-tin-tai-khoan') }}" target="">Lịch sử đặt sân</a></li>
                                    <li><a href="{{ route('thong-tin-ca-nhan') }}" target="">Thông tin cá nhân</a></li>
                                    <li><a href="{{ route('thay-doi-mat-khau') }}" target="">Thay đổi mật khẩu</a></li>
                                </ul>
                            </li>
                        </div>  
                    </div>
                </div>

                <div class="admin">
                    <div class="admin-section-right"> 
                        @yield('content') <!-- Phần nội dung chính sẽ được hiển thị ở đây -->
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!-- End: Content -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Powered by Group 7</p>
        </div>
        <!-- End: Footer -->

    </div>
</body>
</html>
