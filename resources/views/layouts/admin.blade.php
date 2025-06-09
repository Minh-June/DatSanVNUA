<!-- resources/views/layouts/admin.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đặt sân thể thao</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-free-6.5.2/css/all.min.css') }}">
    @stack('styles')
</head>
<body>
    <div id="main">
        
        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="{{ route('admin') }}" target="_self">Đặt sân thể thao</a>
            
            <div class="header-login">
                <a class="signup-btn" href="{{ route('dang-nhap') }}" target="_self">Đăng xuất</a>
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
                                @php
                                    $user = Auth::user();
                                @endphp
                                <a class="avatar-name" href="{{ route('thong-tin-tai-khoan') }}" target="_self">
                                    {{ $user->username }}
                                </a>
                            @else
                                <a class="avatar-name" href="{{ route('dang-nhap') }}" target="_self">
                                    Đăng Nhập
                                </a>
                            @endif
                        </div>
                        
                        <div class="admin-manage">
                            <li>
                                <a href="{{ route('quan-ly-nguoi-dung') }}">Quản lý người dùng</a>
                            </li>
                            <li>
                                <a href="{{ route('quan-ly-loai-san') }}">Quản lý loại sân</a>
                            </li>
                            <li>
                                <a href="{{ route('quan-ly-san') }}">Quản lý sân</a>
                            </li>
                            <li>
                                <a href="{{ route('quan-ly-don-dat-san') }}">Quản lý đơn đặt sân</a>
                            </li>
                            <li>
                                <a href="{{ route('thong-ke-bao-cao') }}">Thống kê, báo cáo</a>
                            </li>
                            <li>
                                <a href="{{ route('thong-tin-tai-khoan') }}">Quản lý tài khoản</a>
                            </li>
                        </div>
                    </div>
                </div>

                <div class="admin">
                    <div class="admin-section-right">
                        <!-- Nội dung chính sẽ được chèn ở đây -->
                        @yield('content')
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!-- End: Content -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Powered by Group 48</p>
        </div>
        <!-- End: Footer -->

    </div>
    @stack('scripts')

    <!-- Khung popup hiển thị ảnh -->
    <div id="image-popup" onclick="hideImage()" style="
        display: none;
        position: fixed;
        z-index: 2;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.7);
        justify-content: center;
        align-items: center;
    ">
        <img id="popup-img" src="" style="max-height: 700px; max-width: 525px; box-shadow: 0 0 10px #000;">
    </div>

    <script>
        function showImage(src) {
            document.getElementById('popup-img').src = src;
            document.getElementById('image-popup').style.display = 'flex';
        }

        function hideImage() {
            document.getElementById('image-popup').style.display = 'none';
        }
    </script>
</body>
</html>
