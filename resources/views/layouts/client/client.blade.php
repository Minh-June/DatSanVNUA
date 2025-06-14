<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ĐẶT SÂN VNUA</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-free-6.5.2/css/all.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
    <div id="main">
        <!-- Begin: Header -->
        <div id="header">
            <ul id="nav">
                <li>
                    <a class="home-heading" href="{{ route('trang-chu') }}" target="_top">
                        <i class="fa-solid fa-house"></i>TRANG CHỦ
                    </a>
                </li>
                <li>
                    @if (Route::currentRouteName() === 'trang-chu')
                        <a class="home-heading search-btn" href="#">
                            <i class="fa-solid fa-magnifying-glass"></i>TÌM SÂN NHANH
                        </a>
                    @endif
                </li>
            </ul>
            
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
                                    // Key nhóm theo sân và ngày
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
                                <div class="header__cart-list-no-cart-msg">Hiện chưa có đơn đặt sân nào</div>
                            </div>
                        @else
                            <div class="header__cart-list">
                                <div class="header__cart-heading">Danh sách đơn đặt sân</div>
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
                                                    <p class="header__cart-item-remove">
                                                        Ngày: {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}
                                                    </p>
                                                    <p class="header__cart-item-description">
                                                        {!! implode('<br>', $order['times']) !!}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <button class="header__cart-view-cart"
                                        onclick="window.location='{{ route('xac-nhan-dat-san') }}'">
                                    Xác nhận đặt sân
                                </button>
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
                    <a class="signup-btn" 
                    href="{{ $user->role != 1 ? route('thong-ke-bao-cao') : route('thong-tin-tai-khoan') }}" 
                    target="_self">
                        {{ $user->username }}
                    </a>
                @else
                    <a class="signup-btn" href="{{ route('dang-nhap') }}" target="_self">Đăng nhập</a>
                @endif
            </div>
        </div>
        <!-- End: Header -->

        @yield('content') <!-- Nơi để nội dung của các trang khác được chèn vào -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Designed by Group 48</p>
        </div>
        <!-- End: Footer -->
    </div>
    
    <!-- Modal tìm kiếm sân -->
    <div class="modal js-modal">
        <div class="modal-container js-modal-container">
            <div class="modal-close js-modal-close">
                <i class="fa-solid fa-xmark"></i>
            </div>
            
            <div class="modal-header">TÌM KIẾM</div>
                <form class="modal-body" method="GET" action="{{ route('tim-kiem') }}">
                    <label for="date">Chọn ngày:</label>
                    <input type="date" id="date" name="date"
                        value="{{ old('date', $selected_date ?? date('Y-m-d')) }}"
                        min="{{ date('Y-m-d') }}"
                        onchange="onDateChange()">

                    <div class="form-group">
                        <label class="modal-label" for="type">Chọn loại sân:</label>
                        <select name="type" id="type">
                            @foreach ($types as $type)
                                <option value="{{ $type->type_id }}" {{ old('type') == $type->type_id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="modal-label" for="type">Khung giờ từ:</label>
                        <select name="type" id="type">
                            @foreach ($types as $type)
                                <option value="{{ $type->type_id }}" {{ old('type') == $type->type_id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="order-football-btn">Tìm kiếm</button>
                </form>
        </div>
    </div>

    <script>
        const modal = document.querySelector('.js-modal');
        const modalContainer = document.querySelector('.js-modal-container');
        const modalClose = document.querySelector('.js-modal-close');

        // Hiển thị modal
        function showModal() {
            modal.classList.add('open');
        }

        // Ẩn modal
        function hideModal() {
            modal.classList.remove('open');
        }

        // Gán sự kiện cho nút "Tìm kiếm"
        const searchBtn = document.querySelector('.search-btn');
        if (searchBtn) {
            searchBtn.addEventListener('click', function(event) {
                event.preventDefault(); // Ngăn chuyển trang nếu là <a>
                showModal();
            });
        }

        // Đóng modal khi click nút X
        modalClose.addEventListener('click', hideModal);

        // Đóng modal khi click ra ngoài
        modal.addEventListener('click', hideModal);

        // Ngăn sự kiện lan ra ngoài modal-container
        modalContainer.addEventListener('click', function (event) {
            event.stopPropagation();
        });

        document.querySelector('.modal-body').addEventListener('submit', function (event) {
            const timeFrom = document.getElementById('time_from').value;
            const timeTo = document.getElementById('time_to').value;

            // Biểu thức kiểm tra định dạng HH:mm (00:00 - 23:59)
            const timeRegex = /^([01]\d|2[0-3]):([0-5]\d)$/;

            if (!timeRegex.test(timeFrom) || !timeRegex.test(timeTo)) {
                alert('Vui lòng nhập đúng định dạng giờ theo mẫu !');
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
