<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Äáº·t sĂ¢n thá»ƒ thao</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-free-6.5.2/css/all.min.css') }}">
</head>
<body>
    <div id="main">

        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="{{ route('trang-chu') }}" target="_top">Äáº·t sĂ¢n thá»ƒ thao</a>
            
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
                                    // Key nhĂ³m theo sĂ¢n vĂ  ngĂ y: yard_id + date
                                    $key = $order['yard_id'] . '_' . $order['date'];
                                    if (!isset($groupedOrders[$key])) {
                                        $groupedOrders[$key] = $order;
                                        // Máº£ng lÆ°u táº¥t cáº£ giá» Ä‘Ă£ chá»n cho nhĂ³m nĂ y
                                        $groupedOrders[$key]['times'] = $order['times'];
                                    } else {
                                        // Ná»‘i thĂªm cĂ¡c giá» má»›i (loáº¡i bá» trĂ¹ng)
                                        $groupedOrders[$key]['times'] = array_unique(array_merge($groupedOrders[$key]['times'], $order['times']));
                                        // Cá»™ng dá»“n giĂ¡ tiá»n
                                        $groupedOrders[$key]['price'] += $order['price'];
                                    }
                                }
                            }
                        @endphp

                        @if (empty($groupedOrders))
                            <div class="header__cart-list header__cart-list--no-cart">
                                <div class="header__cart-list-no-cart-msg">Hiá»‡n chÆ°a cĂ³ Ä‘Æ¡n Ä‘áº·t sĂ¢n nĂ o</div>
                            </div>
                        @else
                            <div class="header__cart-list">
                                <div class="header__cart-heading">Danh sĂ¡ch Ä‘Æ¡n Ä‘áº·t sĂ¢n</div>
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
                                                        <span class="header__cart-item-price">{{ number_format($order['price'], 0, ',', '.') }}Ä‘</span>
                                                        <span class="header__cart-item-multiply">x</span>
                                                        <span class="header__cart-item-qnt">{{ count($order['times']) }}</span>
                                                    </div>
                                                </div>
                                                <div class="header__cart-item-body">
                                                    <p class="header__cart-item-remove">
                                                        NgĂ y: {{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}
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
                                    XĂ¡c nháº­n Ä‘áº·t sĂ¢n
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
                    <a class="signup-btn" href="{{ route('thong-tin-tai-khoan') }}" target="_self">
                        {{ $user->username }}
                    </a>
                @else
                    <a class="signup-btn" href="{{ route('dang-nhap') }}" target="_self">ÄÄƒng Nháº­p</a>
                @endif
            </div>
        </div>
        <!-- End: Header -->

        @yield('content') <!-- NÆ¡i Ä‘á»ƒ ná»™i dung cá»§a cĂ¡c trang khĂ¡c Ä‘Æ°á»£c chĂ¨n vĂ o -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Powered by Group 48</p>
        </div>
        <!-- End: Footer -->

    </div>

</body>
</html>
