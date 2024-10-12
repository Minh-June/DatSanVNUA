<!-- resources/views/layouts/admin.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đặt lịch sân thể thao - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-free-6.5.2/css/all.min.css') }}">
    @stack('styles')
</head>
<body>
    <div id="main">
        
        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="{{ route('admin') }}" target="_self">Đặt lịch sân thể thao</a>
            
            <div class="header-login">
                <i class="avatar fa-solid fa-user-tie"></i>
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
                            <a class="avatar-name" href="" target="_self">Admin</a>
                        </div>
                        
                        <div class="admin-manage">
                            <li>
                                <a href="{{ route('quan-ly-khach-hang') }}" target="">Quản lý đơn đặt</a>
                                <ul class="section-left">
                                    <li><a href="{{ route('them-khach-hang') }}" target="">Thêm đơn đặt</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="{{ route('quan-ly-san') }}" target="">Quản lý sân</a>
                                <ul class="section-left">
                                    <li><a href="{{ route('them-san') }}" target="">Thêm sân</a></li>
                                </ul>
                            </li>
                            
                            <li>
                                <a href="{{ route('quan-ly-thoi-gian-san') }}" target="">Quản lý thời gian sân</a>
                                <ul class="section-left">
                                    <li><a href="{{ route('them-thoi-gian-san') }}" target="">Thêm thời gian sân</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="{{ route('quan-ly-hinh-anh-san') }}" target="">Quản lý hình ảnh sân</a>
                                <ul class="section-left">
                                    <li><a href="{{ route('them-hinh-anh-san') }}" target="">Thêm hình ảnh sân</a></li>
                                </ul>
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
            <p class="copyright">Powered by MJ</p>
        </div>
        <!-- End: Footer -->

    </div>

    @stack('scripts')
</body>
</html>
