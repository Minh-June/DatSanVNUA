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
                    <a class="home-heading" href="{{ route('trang-chu') }}" target="_top">
                        <i class="fa-solid fa-house"></i>TRANG CHỦ
                    </a>
                </li>
                <li>
                    <a class="home-heading search-btn" href="#">
                        <i class="fa-solid fa-magnifying-glass"></i>TÌM KIẾM
                    </a>
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
                                                    <div class="header__cart-item-name">{{ $order['type_name'] }}</div>
                                                    <div class="header__cart-item-price-wrap">
                                                        <span class="header__cart-item-price">{{ number_format($order['price'], 0, ',', '.') }}đ</span>
                                                        <span class="header__cart-item-multiply">x</span>
                                                        <span class="header__cart-item-qnt">{{ count($order['times']) }}</span>
                                                    </div>
                                                </div>
                                                <div class="header__cart-item-body">
                                                    <div class="header__cart-item-body-left">
                                                        <div class="header__cart-item-name">{{ $order['yard_name'] }}</div>
                                                        <p class="header__cart-item-remove">
                                                            Ngày: {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}
                                                        </p>
                                                    </div>
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
                @php
                    $user = Auth::user();
                @endphp

                <a class="signup-btn"
                href="{{ Auth::check() ? ($user->role != 1 ? route('thong-ke-bao-cao') : route('thong-tin-tai-khoan')) : route('dang-nhap') }}"
                target="_self">
                    {{ Auth::check() ? $user->username : 'Đăng nhập' }}
                </a>
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
                            @foreach ($types as $type)
                                <option value="{{ $type->type_id }}" {{ old('type') == $type->type_id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group time-range">
                        <label for="time_from">Từ:</label>
                        <input type="text" id="time_from" name="time_from" placeholder="Dạng 06:00" required>

                        <label for="time_to">đến</label>
                        <input type="text" id="time_to" name="time_to" placeholder="Dạng 22:00" required>
                    </div>

                    <button type="submit" class="order-football-btn">Tìm kiếm</button>
                </form>
        </div>
    </div>

    <script>
        // Hiện Model tìm kiếm
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

        // Trượt slider
        const track = document.querySelector('.slider-track');
        const slides = document.querySelectorAll('.slider-track img'); // Tất cả ảnh
        const totalSlides = slides.length; // Tổng số ảnh (cả ảnh clone)
        let currentIndex = 0; // Vị trí hiện tại

        // Trượt tự động mỗi 3 giây
        let autoSlide = setInterval(() => {
            currentIndex++; // Tăng vị trí
            track.style.transition = 'transform 0.5s ease-in-out';
            track.style.transform = `translateX(-${currentIndex * 100}%)`; // Trượt sang trái

            // Nếu đang ở ảnh clone (cuối)
            if (currentIndex === totalSlides - 1) {
                setTimeout(() => {
                    track.style.transition = 'none';              // Tắt hiệu ứng
                    track.style.transform = 'translateX(0%)';     // Về ảnh đầu
                    currentIndex = 0;
                }, 500); // Khớp với thời gian transition
            }
        }, 3000);

        // Ấn nút chuyển slider
        const btnLeft = document.querySelector('.slider-btn-left');
        const btnRight = document.querySelector('.slider-btn-right');

        if (btnLeft && btnRight) {
            // Nút sang phải
            btnRight.addEventListener('click', () => {
                clearInterval(autoSlide); // Dừng auto
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

            // Nút sang trái
            btnLeft.addEventListener('click', () => {
                clearInterval(autoSlide); // Dừng auto

                if (currentIndex === 0) {
                    // Nhảy về ảnh cuối thật (trước ảnh clone)
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
