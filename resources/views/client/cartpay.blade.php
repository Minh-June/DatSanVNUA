@extends('layouts.client.client')

@section('title', 'Thanh toán sản phẩm')

@section('content')
<div id="content" class="order-section">
    <h2 class="order-heading">THANH TOÁN</h2>

    {{-- Chọn hình thức thanh toán --}}
    <div class="pay-method" style="margin-bottom: 20px;">
        <p>Hình thức thanh toán</p>
        <label style="margin-right: 20px;">
            <input type="radio" name="payment_method" value="offline" checked>
            Thanh toán khi nhận hàng
        </label>
        <label>
            <input type="radio" name="payment_method" value="online">
            Thanh toán trực tuyến
        </label>
    </div>
        
    <form method="POST" action="{{ route('pay.product.offline') }}">
        @csrf
        
        <div class="cart-pay-wrapper">
            {{-- Thông tin thanh toán --}}
            <div class="cart-pay-left" id="paymentInfo">
                <div class="adminedit">
                    <h2 style="text-align:center;">Thông tin thanh toán</h2>

                    <div class="adminedit-form-group" style="margin-top:20px;">
                        <label for="fullname">Họ và tên:</label>
                        <input type="text" name="fullname" value="{{ old('fullname', $user->fullname) }}" required>
                    </div>

                    <div class="adminedit-form-group">
                        <label for="phonenb">Số điện thoại:</label>
                        <input type="text" name="phonenb" value="{{ old('phonenb', $user->phonenb) }}" required>
                    </div>

                    <div class="adminedit-form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="adminedit-form-group">
                        <label for="address">Địa chỉ:</label>
                        <textarea name="address" rows="4" placeholder="Số nhà - Đường - Phường/xã - Tỉnh thành..." required>{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div class="adminedit-form-group" style="margin-bottom:30px;">
                        <label for="notes">Ghi chú:<br>(tùy chọn)</label>
                        <textarea name="notes" rows="4" placeholder="Ví dụ: thời gian giao hàng...">{{ old('notes') }}</textarea>
                    </div>
                    
                </div>
            </div>

            {{-- Bảng sản phẩm --}}
            <div class="cart-pay-right">
                @if(count($buys) > 0)
                    <table id="ListCustomers">
                        <thead>
                            <tr>
                                <th colspan="2">Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th class="online-only" style="display:none;">Tùy chọn</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach(collect($buys)->groupBy('store_id') as $storeId => $storeItems)
                            @php
                                $store = \App\Models\Store::find($storeId);
                                $storeName = $store->name ?? 'Shop không xác định';

                                // Gộp sản phẩm giống nhau theo name + size
                                $mergedItems = [];
                                foreach ($storeItems as $item) {
                                    $sizeName = $item['product_size_id'] ? \App\Models\ProductSize::find($item['product_size_id'])->name : '';
                                    $key = $item['name'] . '_' . $sizeName;
                                    if(isset($mergedItems[$key])) {
                                        $mergedItems[$key]['quantity'] += $item['quantity'];
                                    } else {
                                        $item['size_name'] = $sizeName; // lưu tên size để hiển thị
                                        $mergedItems[$key] = $item;
                                    }
                                }
                                $mergedItems = array_values($mergedItems);
                                $totalStorePrice = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $mergedItems));
                            @endphp

                            {{-- Tên shop --}}
                            <tr class="name-shop-cart-pay" onclick="window.location='{{ route('chi-tiet-cua-hang', $storeId) }}'" style="cursor:pointer;">
                                <td colspan="6" class="left-align">
                                    <img src="{{ $store->user && $store->user->image ? asset('storage/' . $store->user->image) : asset('images/default-avatar.png') }}" 
                                         alt="{{ $storeName }}">
                                    <span>{{ $storeName }}</span>
                                </td>
                            </tr>

                            {{-- Sản phẩm --}}
                            @foreach($mergedItems as $index => $item)
                                <tr>
                                    <td style="cursor:pointer;" onclick="window.location='{{ route('chi-tiet-san-pham', $item['product_id']) }}'">
                                        <img src="{{ asset('storage/' . ($item['image'] ?? 'image/football.jpg')) }}" width="80">
                                    </td>
                                    <td class="left-align product-name-cart" style="cursor:pointer;" onclick="window.location='{{ route('chi-tiet-san-pham', $item['product_id']) }}'">
                                        @php
                                            $fullName = $item['name'] ?? '';
                                            $sizeText = !empty($item['size_name']) ? '- Size ' . $item['size_name'] : '';
                                            
                                            // Nếu có size, tách phần size riêng
                                            $words = explode(' ', $fullName);
                                            $chunks = array_chunk($words, 6);
                                        @endphp

                                        {{-- Hiển thị phần tên --}}
                                        @foreach($chunks as $chunk)
                                            {{ implode(' ', $chunk) }}<br>
                                        @endforeach

                                        {{-- Hiển thị size ở dòng riêng nếu có --}}
                                        @if($sizeText)
                                            {{ $sizeText }}
                                        @endif
                                    </td>
                                    <td>{{ number_format($item['price']) }}đ</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>{{ number_format($item['price'] * $item['quantity']) }}đ</td>

                                    {{-- Nút thanh toán online (chỉ 1 ô) --}}
                                    @if($index === 0)
                                        <td class="online-only" style="text-align:center;" rowspan="{{ count($mergedItems) }}">
                                            <button type="button" class="order-football-btn btn-pay" data-owner="{{ $store->store_id }}">
                                                Thanh toán
                                            </button>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach

                            {{-- Tổng tiền shop --}}
                            <tr>
                                <td colspan="4" style="font-weight:bold; text-align:right;">Tổng tiền:</td>
                                <td colspan="2" style="font-weight:bold;" id="shopTotal_{{ $storeId }}">
                                    {{ number_format($totalStorePrice) }}đ
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Giỏ hàng của bạn trống.</p>
                @endif
            </div>
        </div>

        {{-- Ẩn input gửi dữ liệu sản phẩm --}}
        @foreach($buys as $item)
            <input type="hidden" name="products[{{ $item['product_id'] }}][name]" value="{{ $item['name'] }}">
            <input type="hidden" name="products[{{ $item['product_id'] }}][price]" value="{{ $item['price'] }}">
            <input type="hidden" name="products[{{ $item['product_id'] }}][quantity]" value="{{ $item['quantity'] }}">
            <input type="hidden" name="products[{{ $item['product_id'] }}][product_size_id]" value="{{ $item['product_size_id'] ?? '' }}">
            <input type="hidden" name="products[{{ $item['product_id'] }}][store_id]" value="{{ $item['store_id'] ?? 0 }}">
        @endforeach

        {{-- Nút xác nhận thanh toán offline --}}
        @if(count($buys) > 0)
        <div class="footer-link" style="margin-top:50px;" id="offlineSubmitBtn">
            <button type="submit" class="order-football-btn">Xác nhận mua hàng</button>
        </div>
        @endif
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const onlineCells = document.querySelectorAll('.online-only');
    const cartWrapper = document.querySelector('.cart-pay-wrapper');
    const offlineSubmitBtn = document.getElementById('offlineSubmitBtn');
    const paymentInfo = document.getElementById('paymentInfo');

    function updateTableColumn(value) {
        const isOnline = value === 'online';

        // Hiện / ẩn cột tùy chọn
        onlineCells.forEach(cell => {
            cell.style.display = isOnline ? 'table-cell' : 'none';
        });

        // Hiện / ẩn nút offline
        if (offlineSubmitBtn) {
            offlineSubmitBtn.style.display = isOnline ? 'none' : 'block';
        }

        // Thêm / xóa class online-mode cho wrapper
        if (cartWrapper) {
            if (isOnline) {
                cartWrapper.classList.add('online-mode');
            } else {
                cartWrapper.classList.remove('online-mode');
            }
        }
    }

    // Chạy khi load trang
    updateTableColumn(document.querySelector('input[name="payment_method"]:checked').value);

    // Lắng nghe thay đổi radio
    radios.forEach(r => {
        r.addEventListener('change', function() {
            updateTableColumn(this.value);
        });
    });

    // Nút thanh toán online
    document.querySelectorAll('.btn-pay').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const ownerId = this.dataset.owner;
            if (ownerId) {
                window.location.href = `/thanh-toan-gio-hang/online/${ownerId}`;
            } else {
                alert('Không tìm thấy chủ cửa hàng !');
            }
        });
    });
});
</script>
@endsection
