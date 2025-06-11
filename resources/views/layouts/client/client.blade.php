<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đặt sân thể thao</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-free-6.5.2/css/all.min.css') }}">
</head>
<body>
    <div id="main">

        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="{{ route('trang-chu') }}" target="_top">Đặt sân thể thao</a>
            
            <div class="header-login">
                <!-- Carlender layout -->
                <div class="header__carlender">
                    <div class="header__cart-wrap">
                        <i class="header__cart-icon fa-solid fa-calendar"></i>
                        <span class="header__cart-notice">
                            {{ session('orders') ? count(session('orders')) : 0 }}
                        </span>

                        @php
                            $groupedOrders = [];
                            if (session('orders')) {
                                foreach (session('orders') as $order) {
                                    // Key nhóm theo sân và ngày: yard_id + date
                                    $key = $order['yard_id'] . '_' . $order['date'];
                                    if (!isset($groupedOrders[$key])) {
                                        $groupedOrders[$key] = $order;
                                        // Mảng lưu tất cả giờ đã chọn cho nhóm này
                                        $groupedOrders[$key]['times'] = $order['times'];
                                    } else {
                                        // Nối thêm các giờ mới (loại bỏ trùng)
                                        $groupedOrders[$key]['times'] = array_unique(array_merge($groupedOrders[$key]['times'], $order['times']));
                                        // Cộng dồn giá tiền
                                        $groupedOrders[$key]['price'] += $order['price'];
                                    }
                                }
                            }
                        @endphp

                        @if (empty($groupedOrders))
                            <div class="header__cart-list header__cart-list--no-cart">
<<<<<<< HEAD
                                <div class="header__cart-list-no-cart-msg">Chưa có sân và khung giờ được đặt</div>
                            </div>
                        @else
                            <div class="header__cart-list">
                                <div class="header__cart-heading">Các sân và khung giờ đã đặt</div>
=======
                                <div class="header__cart-list-no-cart-msg">Hiện chưa có đơn đặt sân nào</div>
                            </div>
                        @else
                            <div class="header__cart-list">
                                <div class="header__cart-heading">Danh sách đơn đặt sân</div>
>>>>>>> 80d6e7c (Cập nhật giao diện)
                                <ul class="header__cart-list-item">
                                    @foreach($groupedOrders as $key => $order)
                                        <li class="header__cart-item">
                                            <img 
                                                src="{{ $yardFirstImages[$order['yard_id']] ?? asset('image/football.jpg') }}" 
                                                alt="{{ $order['yard_name'] }}" 
                                                class="header__cart-img"
                                            />

                                            <div class="header__cart-item-info">
                                                <div class="header__cart-item-head">
                                                    <div class="header__cart-item-name">{{ $order['yard_name'] }}</div>
                                                    <div class="header__cart-item-price-wrap">
                                                        <span class="header__cart-item-price">{{ number_format($order['price'], 0, ',', '.') }}đ</span>
                                                        <span class="header__cart-item-multiply">x</span>
                                                        <span class="header__cart-item-qnt">{{ count($order['times']) }}</span>
                                                    </div>
                                                </div>
                                                <div class="header__cart-item-body">
<<<<<<< HEAD
                                                    <span class="header__cart-item-remove">
                                                        {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}
                                                    </span>
                                                    <span class="header__cart-item-description">
                                                        {!! implode('<br>', $order['times']) !!}
                                                    </span>
=======
                                                    <p class="header__cart-item-remove">
                                                        Ngày: {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}
                                                    </p>
                                                    <p class="header__cart-item-description">
                                                        {!! implode('<br>', $order['times']) !!}
                                                    </p>
>>>>>>> 80d6e7c (Cập nhật giao diện)
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
<<<<<<< HEAD

                                <a href="{{ route('xac-nhan-dat-san') }}" class="header__cart-view-cart">Xác nhận đặt sân</a>
=======
                                <button class="header__cart-view-cart"
                                        onclick="window.location='{{ route('xac-nhan-dat-san') }}'">
                                    Xác nhận đặt sân
                                </button>
>>>>>>> 80d6e7c (Cập nhật giao diện)
                            </div>
                        @endif

                    </div>
                </div>
                
                <i class="login-btn dash"></i>
                <i class="avatar fa-solid fa-user-tie"></i>
                @if (Auth::check())
                    @php
                        $user = Auth::user();
                    @endphp
                    <a class="signup-btn" href="{{ route('thong-tin-tai-khoan') }}" target="_self">
                        {{ $user->username }}
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
            <p class="copyright">Powered by Group 48</p>
        </div>
        <!-- End: Footer -->

    </div>

</body>
</html>
