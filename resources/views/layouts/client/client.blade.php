<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ĐẶT SÂN VNUA</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-free-6.5.2/css/all.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
    @if ($errors->any())
        <script>
            let errorMessages = @json($errors->all());
            alert(errorMessages.join('\n'));
            // Mở lại modal để người dùng thấy lỗi
            window.onload = () => {
                document.querySelector('.js-modal')?.classList.add('open');
            };
        </script>
    @endif

    @if (!Auth::check() && request()->route()->getName() !== 'dang-nhap')
        <script>
            alert("Phiên đăng nhập hết hạn, vui lòng đăng nhập lại !");
            window.location.href = "{{ route('dang-nhap') }}";
        </script>
    @endif

    <div id="main">
        <!-- Begin: Header -->
        <div id="header">
            <ul id="nav">
                <li>
                    <a class="home-heading {{ request()->routeIs('trang-chu', 'dat-san', 'getBookedTimes', 'luu-thong-tin-don-dat-san', 'xac-nhan-dat-san', 'xoa-don-tam-thoi', 'thanh-toan', 'pay.upload', 'payment.timeout') ? 'active' : '' }}" href="{{ route('trang-chu') }}" target="_top">
                        <i class="fa-solid fa-house"></i>TRANG CHỦ
                    </a>
                </li>
                <li>
                    <a class="home-heading search-btn" href="#">
                        <i class="fa-solid fa-magnifying-glass"></i>TÌM KIẾM
                    </a>
                </li>
                <li>
                    <a class="home-heading {{ 
                            request()->routeIs('danh-sach-cua-hang') || 
                            request()->routeIs('luu-san-pham-storesboard') || 
                            request()->routeIs('chi-tiet-cua-hang') || 
                            request()->routeIs('luu-san-pham-storedetail') || 
                            request()->routeIs('chi-tiet-san-pham') || 
                            request()->routeIs('luu-san-pham-productdetail') || 
                            request()->routeIs('gio-hang') || 
                            request()->routeIs('xoa-san-pham-tam-thoi') || 
                            request()->routeIs('cap-nhat-so-luong') || 
                            request()->routeIs('thanh-toan-gio-hang') || 
                            request()->routeIs('pay.product.offline') || 
                            request()->routeIs('pay.product.now') || 
                            request()->routeIs('pay.product.online')
                        ? 'active' : '' }}" 
                        href="{{ route('danh-sach-cua-hang') }}">
                        <i class="fa-solid fa-cart-shopping"></i> MUA SẮM
                    </a>
                </li>
                <li>
                    <a class="home-heading {{ request()->routeIs('tin-tuc', 'chi-tiet-tin-tuc') ? 'active' : '' }}" href="{{ route('tin-tuc') }}">
                        <i class="fa-solid fa-newspaper"></i>TIN TỨC
                    </a>
                </li>
            </ul>
            
            <div class="header-login">
                <!-- Giỏ hàng -->
                <div class="header__cart"
                    onclick="window.location='{{ route('gio-hang', ['user_id' => Auth::id()]) }}'"
                    style="cursor:pointer;">

                    <div class="header__cart-wrap">
                        <i class="header__cart-icon fa-solid fa-cart-shopping"></i>
                        <span class="header__cart-notice">{{ count(session('buys') ?? []) }}</span>
                        @php
                            $groupedBuys = [];
                            $buysSession = session('buys') ?? [];
                            $sizesMap = \App\Models\ProductSize::pluck('name', 'product_size_id')->toArray();
                            $totalCartPrice = 0;

                            foreach ($buysSession as $item) {
                                $key = $item['product_id'] . '_' . ($item['product_size_id'] ?? '');
                                if (!isset($groupedBuys[$key])) {
                                    $groupedBuys[$key] = $item;
                                } else {
                                    $groupedBuys[$key]['quantity'] += $item['quantity'];
                                }
                            }

                            foreach ($groupedBuys as $item) {
                                $totalCartPrice += $item['price'] * $item['quantity'];
                            }
                        @endphp

                        @if(empty($groupedBuys))
                            <div class="header__cart-list header__cart-list--no-cart">
                                <div class="header__cart-list-no-cart-msg">Hiện chưa có sản phẩm nào</div>
                            </div>
                        @else
                            <div class="header__cart-list">
                                <div class="header__cart-heading">Giỏ hàng</div>
                                <ul class="header__cart-list-item">
                                    @foreach($groupedBuys as $item)
                                        <li class="header__cart-item">
                                            <img src="{{ asset('storage/' . $item['image']) ?? asset('images/no-image.png') }}" 
                                                alt="{{ $item['name'] }}" class="header__cart-img" />
                                            <div class="header__cart-item-info">
                                                <div class="header__cart-item-name">{{ $item['name'] }}</div>
                                                <div class="header__cart-item-details">
                                                    <span>{{ $item['quantity'] }} x {{ $item['price'] ? number_format($item['price'], 0, ',', '.') . 'đ' : 'Không có giá' }}</span>
                                                </div>
                                                @if(!empty($item['product_size_id']))
                                                    <div class="header__cart-item-size">
                                                        Size: {{ $sizesMap[$item['product_size_id']] ?? 'Không xác định' }}
                                                    </div>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="header__cart-total">
                                    <strong>Thành tiền:</strong> {{ number_format($totalCartPrice, 0, ',', '.') }}đ
                                </div>
                                <button class="header__cart-view-cart"
                                        onclick="window.location='{{ route('gio-hang', ['user_id' => Auth::id()]) }}'">
                                    Xem giỏ hàng
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Lịch đặt -->
                <div class="header__calendar" 
                    onclick="window.location='{{ route('xac-nhan-dat-san') }}'" 
                    style="cursor:pointer;">
                    <div class="header__cal-wrap">
                        <i class="header__cal-icon fa-solid fa-calendar"></i>
                        <span class="header__cal-notice">
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
                            <div class="header__cal-list header__cal-list--no-cal">
                                <div class="header__cal-list-no-cal-msg">Hiện chưa có đơn đặt sân nào</div>
                            </div>
                        @else
                            <div class="header__cal-list">
                                <div class="header__cal-heading">Danh sách đơn đặt sân</div>
                                <ul class="header__cal-list-item">
                                    @foreach($groupedOrders as $key => $order)
                                        <li class="header__cal-item">
                                            <img 
                                                src="{{ $yardFirstImages[$order['yard_id']] ?? asset('image/football.jpg') }}" 
                                                alt="{{ $order['yard_name'] }}" 
                                                class="header__cal-img"
                                            />

                                            <div class="header__cal-item-info">
                                                <div class="header__cal-item-head">
                                                    <div class="header__cal-item-name">{{ $order['type_name'] }}</div>
                                                    <div class="header__cal-item-price-wrap">
                                                        <span class="header__cal-item-price">{{ number_format($order['price'], 0, ',', '.') }}đ</span>
                                                        <span class="header__cal-item-multiply">x</span>
                                                        <span class="header__cal-item-qnt">{{ count($order['times']) }}</span>
                                                    </div>
                                                </div>
                                                <div class="header__cal-item-body">
                                                    <div class="header__cal-item-body-left">
                                                        <div class="header__cal-item-name">{{ $order['yard_name'] }}</div>
                                                        <p class="header__cal-item-remove">
                                                            Ngày: {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}
                                                        </p>
                                                    </div>
                                                    <p class="header__cal-item-description">
                                                        {!! implode('<br>', $order['times']) !!}
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <button class="header__cal-view-cal"
                                        onclick="window.location='{{ route('xac-nhan-dat-san') }}'">
                                    Xác nhận đặt sân
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Thông báo 
                <div class="header__bell">
                    <div class="header__bell-wrap">
                        <i class="header__bell-icon fa-solid fa-bell"></i>
                        <span class="header__bell-notice">
                            {{ session('notices') ? count(session('notices')) : 0 }}
                        </span>

                        @php
                            $groupedBuys = [];
                            $buysSession = session('buys') ?? [];
                            $totalCartPrice = 0;
                            if (!empty($buysSession)) {
                                foreach ($buysSession as $item) {
                                    $key = $item['product_id'] . '_' . ($item['size'] ?? '');
                                    if (!isset($groupedBuys[$key])) {
                                        $groupedBuys[$key] = $item;
                                    } else {
                                        $groupedBuys[$key]['quantity'] += $item['quantity'];
                                        $groupedBuys[$key]['price'] += $item['price'] * $item['quantity'];
                                    }
                                    $totalCartPrice += $item['price'] * $item['quantity'];
                                }
                            }
                        @endphp

                        @if(empty($groupedBuys))
                            <div class="header__cart-list header__cart-list--no-cart">
                                <div class="header__cart-list-no-cart-msg">Hiện chưa có sản phẩm nào</div>
                            </div>
                        @else
                            <div class="header__cart-list">
                                <div class="header__cart-heading">Giỏ hàng</div>
                                <ul class="header__cart-list-item">
                                    @foreach($groupedBuys as $item)
                                        <li class="header__cart-item">
                                            <img src="{{ asset('storage/' . $item['image']) ?? asset('images/no-image.png') }}" 
                                                alt="{{ $item['name'] }}" class="header__cart-img" />
                                            <div class="header__cart-item-info">
                                                <div class="header__cart-item-name">{{ $item['name'] }}</div>
                                                <div class="header__cart-item-details">
                                                    <span>{{ $item['quantity'] }} x {{ number_format($item['price'],0,',','.') }}đ</span>
                                                </div>
                                                @if(!empty($item['size']))
                                                    <div class="header__cart-item-size">Size: {{ $item['size'] }}</div>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="header__cart-total">
                                    <p>Thành tiền:</p> {{ number_format($totalCartPrice,0,',','.') }}đ
                                </div>
                                <button class="header__cart-view-cart" onclick="window.location='{{ route('xac-nhan-dat-san') }}'">
                                    Xem giỏ hàng
                                </button>
                            </div>
                        @endif
                    </div>
                </div> -->
                
                <i class="login-btn dash"></i>
                @php
                    $user = Auth::user();
                @endphp

                @if (Auth::check())
                    <a href="{{ $user->role != 1 ? route('thong-ke-bao-cao') : route('client.fixed-orders') }}" target="_self">
                        @if (!empty($user->image))
                            <img src="{{ asset('storage/' . $user->image) }}" alt="Avatar" class="user-avatar">
                        @else
                            <i class="avatar fa-solid fa-user-tie"></i>
                        @endif
                    </a>
                @else
                    <a href="{{ route('dang-nhap') }}" target="_self">
                        <i class="avatar fa-solid fa-user-tie"></i>
                    </a>
                @endif

                <a class="signup-btn"
                href="{{ Auth::check() ? ($user->role != 1 ? route('thong-ke-bao-cao') : route('client.fixed-orders')) : route('dang-nhap') }}"
                target="_self">
                {{ Auth::check() ? $user->username : 'Đăng nhập' }}
                </a>
            </div>
        </div>
        <!-- End: Header -->

        @yield('content') <!-- Nơi để nội dung của các trang khác được chèn vào -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Designed by M</p>
        </div>
        <!-- End: Footer -->
    </div>
    
    <!-- Modal tìm kiếm sân -->
    <div class="modal js-modal">
        <div class="modal-container js-modal-container">
            <div class="modal-close js-modal-close">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <div class="modal-header">TÌM SÂN NHANH</div>
            <form class="modal-body" method="GET" action="{{ route('tim-kiem') }}">
                <label for="date">Chọn ngày:</label>
                <input type="date" id="date" name="date"
                    value="{{ old('date', $selected_date ?? date('Y-m-d')) }}"
                    min="{{ date('Y-m-d') }}"
                    onchange="onDateChange()">

                <div class="form-group">
                    <label class="modal-label" for="type">Chọn loại sân:</label>
                    <select name="type">
                        <option value="">Tất cả</option>
                        @if(!empty($types))
                            @foreach ($types as $type)
                                <option value="{{ $type->type_id }}" {{ old('type') == $type->type_id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group time-range">
                    <label for="time_from">Từ</label>
                    <input type="time" id="time_from" name="time_from"
                        value="{{ old('time_from', '06:00') }}" required>

                    <label for="time_to">đến</label>
                    <input type="time" id="time_to" name="time_to"
                        value="{{ old('time_to', '22:00') }}" required>
                </div>

                <button type="submit" class="order-football-btn">Tìm kiếm</button>
            </form>
        </div>
    </div>

    <script>
        // Hiện modal tìm kiếm
        const modal = document.querySelector('.js-modal');
        const modalContainer = document.querySelector('.js-modal-container');
        const modalClose = document.querySelector('.js-modal-close');
        const searchBtn = document.querySelector('.search-btn');
        const nav = document.getElementById('nav');

        // Hiển thị modal
        function showModal() {
            modal.classList.add('open');
            nav.classList.add('modal-open'); // Thêm class để CSS chỉ hover nút tìm kiếm
        }

        // Ẩn modal
        function hideModal() {
            modal.classList.remove('open');
            nav.classList.remove('modal-open'); // Xóa class khi đóng modal
        }

        // Gán sự kiện cho nút "Tìm kiếm"
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
        modalContainer.addEventListener('click', function(event) {
            event.stopPropagation();
        });

        // Kiểm tra định dạng giờ khi submit
        document.querySelector('.modal-body').addEventListener('submit', function(event) {
            const timeFrom = document.getElementById('time_from').value;
            const timeTo = document.getElementById('time_to').value;
            const timeRegex = /^([01]\d|2[0-3]):([0-5]\d)$/;

            if (!timeRegex.test(timeFrom) || !timeRegex.test(timeTo)) {
                alert('Vui lòng nhập đúng định dạng giờ theo mẫu !');
                event.preventDefault();
            }
        });

        // Trượt slider
        const track = document.querySelector('.slider-track');
        const slides = document.querySelectorAll('.slider-track img');
        const totalSlides = slides.length;
        let currentIndex = 0;

        let autoSlide = setInterval(() => {
            currentIndex++;
            track.style.transition = 'transform 0.5s ease-in-out';
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
            if (currentIndex === totalSlides - 1) {
                setTimeout(() => {
                    track.style.transition = 'none';
                    track.style.transform = 'translateX(0%)';
                    currentIndex = 0;
                }, 500);
            }
        }, 3000);

        // Nút slider
        const btnLeft = document.querySelector('.slider-btn-left');
        const btnRight = document.querySelector('.slider-btn-right');

        if (btnLeft && btnRight) {
            btnRight.addEventListener('click', () => {
                clearInterval(autoSlide);
                currentIndex++;
                track.style.transition = 'transform 0.5s ease-in-out';
                track.style.transform = `translateX(-${currentIndex * 100}%)`;
                if (currentIndex === totalSlides - 1) {
                    setTimeout(() => {
                        track.style.transition = 'none';
                        track.style.transform = 'translateX(0%)';
                        currentIndex = 0;
                    }, 500);
                }
            });

            btnLeft.addEventListener('click', () => {
                clearInterval(autoSlide);
                if (currentIndex === 0) {
                    currentIndex = totalSlides - 2;
                    track.style.transition = 'none';
                    track.style.transform = `translateX(-${(currentIndex + 1) * 100}%)`;
                    setTimeout(() => {
                        track.style.transition = 'transform 0.5s ease-in-out';
                        track.style.transform = `translateX(-${currentIndex * 100}%)`;
                    }, 20);
                } else {
                    currentIndex--;
                    track.style.transition = 'transform 0.5s ease-in-out';
                    track.style.transform = `translateX(-${currentIndex * 100}%)`;
                }
            });
        }
    </script>

</body>
</html>
