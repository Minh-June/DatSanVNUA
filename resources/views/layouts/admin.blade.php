<!-- resources/views/layouts/admin.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ĐẶT SÂN VNUA</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-free-6.5.2/css/all.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <div id="main">
        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="{{ route('admin') }}" target="_self">QUẢN LÝ SÂN THỂ THAO</a>
            
            <div class="header-login">
                <form action="{{ route('dang-xuat') }}" method="post" onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
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
                                <script>
                                    window.location.href = "{{ route('dang-nhap') }}";
                                </script>
                            @endif
                        </div>
                        
                        <div class="admin-manage">
                            @php $user = Auth::user(); @endphp

                            @if ($user && $user->role == 0)
                                <li class="{{ request()->is('admin/quan-ly-nguoi-dung*') ? 'active' : '' }}">
                                    <a href="{{ route('quan-ly-nguoi-dung') }}">Quản lý người dùng</a>
                                </li>
                            @endif

                            <li class="{{ request()->is('admin/quan-ly-loai-san*') ? 'active' : '' }}">
                                <a href="{{ route('quan-ly-loai-san') }}">Quản lý loại sân</a>
                            </li>

                            <li class="{{ request()->is('admin/quan-ly-san*') || request()->is('admin/quan-ly-thoi-gian-san*') || request()->is('admin/quan-ly-hinh-anh-san*') ? 'active' : '' }}">
                                <a href="{{ route('quan-ly-san') }}">Quản lý sân</a>
                            </li>

                            <li class="{{ request()->is('admin/quan-ly-don-dat-san*') || request()->is('admin/chi-tiet-don*') ? 'active' : '' }}">
                                <a href="{{ route('quan-ly-don-dat-san') }}">Đơn đặt sân</a>
                            </li>

                            <li class="{{ request()->is('admin/thong-ke-bao-cao*') ? 'active' : '' }}">
                                <a href="{{ route('thong-ke-bao-cao') }}">Thống kê, báo cáo</a>
                            </li>

                            <li class="{{ request()->is('admin/thong-tin-tai-khoan*') ? 'active' : '' }}">
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
            <p class="copyright">Designed by Group 48</p>
        </div>
        <!-- End: Footer -->

    </div>
    @stack('scripts')

    <!-- Xem ảnh trong admin -->
    <div id="image-popup" onclick="hideImage()" style="
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.8);
        justify-content: center;
        align-items: center;
    ">
        <img id="popup-img" src="" style="max-width: 90%; max-height: 90%; box-shadow: 0 0 10px #000;" onclick="event.stopPropagation()">
    </div>

    <script>
        function showImage(src) {
            const popup = document.getElementById('image-popup');
            const popupImg = document.getElementById('popup-img');
            popupImg.src = src;
            popup.style.display = 'flex';
        }

        function hideImage() {
            document.getElementById('image-popup').style.display = 'none';
        }
    </script>
</body>
</html>
