@extends('layouts.client.client')

@section('title', 'Thanh toán sản phẩm online')

@section('content')

<div id="content" class="order-section">
    <h2 class="order-heading">THANH TOÁN TRỰC TUYẾN</h2>

    {{-- Hiển thị alert nếu có lỗi validate --}}
    @if ($errors->any())
        <script>
            alert('{{ $errors->first() }}');
        </script>
    @endif

    {{-- Chọn hình thức thanh toán --}}
    <div class="pay-method" style="margin-bottom: 20px;">
        <p>Hình thức thanh toán</p>
        <label style="margin-right: 20px;">
            <input type="radio" name="payment_method" value="offline">
            Thanh toán khi nhận hàng
        </label>
        <label>
            <input type="radio" name="payment_method" value="online" checked>
            Thanh toán trực tuyến
        </label>
    </div>

    <form method="POST" action="{{ route('pay.product.online') }}" enctype="multipart/form-data">
        @csrf

        {{-- Thông tin ngân hàng --}}
        <div class="pay-customer">
            <p style="text">Thông tin thanh toán</p>
            <div class="pay-content" style="flex-wrap: wrap; margin-bottom: 20px;">
                <div class="pay-information">
                    <div class="bank-account">Thông tin ngân hàng</div>
                    @if($ownerModel)
                        <div class="bank-account">Ngân hàng: {{ $ownerModel->acc_type ?? '' }}</div>
                        <div class="bank-account">Số tài khoản: {{ $ownerModel->acc_number ?? '' }}</div>
                        <div class="bank-account">Tên tài khoản: {{ $ownerModel->acc_name ?? '' }}</div>
                    @endif
                </div>
                <div class="pay-information">
                    @if(!empty($ownerModel?->qr_code))
                        <div class="bank-qr">
                            <img class="bank-qr-img" src="{{ asset('storage/' . $ownerModel->qr_code) }}" alt="Mã QR"><br>
                            Mã QR
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @php
            // Gộp sản phẩm giống nhau theo name + size
            $mergedItems = [];
            foreach ($storeItems as $item) {
                $sizeName = $item['product_size_id'] ? \App\Models\ProductSize::find($item['product_size_id'])->name : '';
                $key = $item['name'] . '_' . $sizeName;
                if(isset($mergedItems[$key])){
                    $mergedItems[$key]['quantity'] += $item['quantity'];
                } else {
                    $mergedItems[$key] = array_merge($item, ['size_name' => $sizeName]);
                }
            }
            $mergedItems = array_values($mergedItems);
            $totalPrice = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $mergedItems));
        @endphp

        @if(count($mergedItems) > 0)
            <input type="hidden" name="store_id" value="{{ $store->store_id }}">

            <div class="cart-pay-wrapper">
                {{-- Thông tin khách hàng --}}
                <div class="cart-pay-left">
                    <div class="adminedit">
                        <h2 style="text-align:center;">Thông tin khách hàng</h2>

                        <div class="adminedit-form-group" style="margin-top:20px;">
                            <label for="fullname">Họ và tên:</label>
                            <input style="margin-left:30px;" type="text" name="fullname" 
                                value="{{ old('fullname', $user->fullname) }}" required>
                        </div>

                        <div class="adminedit-form-group">
                            <label for="phonenb">Số điện thoại:</label>
                            <input type="text" name="phonenb" 
                                value="{{ old('phonenb', $user->phonenb) }}" required>
                        </div>

                        <div class="adminedit-form-group">
                            <label for="email">Email:</label>
                            <input type="email" name="email" 
                                value="{{ old('email', $user->email) }}" required>
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

                {{-- Danh sách sản phẩm --}}
                <div class="cart-pay-right">
                    <table id="ListCustomers">
                        <thead>
                            <tr>
                                <th colspan="2">Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mergedItems as $item)
                                <tr>
                                    <td style="cursor:pointer;" onclick="window.location='{{ route('chi-tiet-san-pham', $item['product_id']) }}'">
                                        <img src="{{ asset('storage/' . ($item['image'] ?? 'image/football.jpg')) }}" width="80">
                                    </td>
                                    <td class="left-align product-name-cart" style="cursor:pointer;"
                                        onclick="window.location='{{ route('chi-tiet-san-pham', $item['product_id']) }}'">
                                        @php
                                            $productName = trim(
                                                $item['name'] . ($item['size_name'] ? ' Size '.$item['size_name'] : '')
                                            );
                                            $nameChunks = $productName
                                                ? array_chunk(explode(' ', $productName), 7)
                                                : [];
                                        @endphp

                                        @foreach($nameChunks as $chunk)
                                            {{ implode(' ', $chunk) }}<br>
                                        @endforeach
                                    </td>
                                    <td>{{ number_format($item['price']) }}đ</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>{{ number_format($item['price'] * $item['quantity']) }}đ</td>
                                </tr>
                            @endforeach
                            {{-- Tổng tiền --}}
                            <tr>
                                <td colspan="4" style="text-align:right; font-weight:bold; font-size:17px;">
                                    Tổng tiền:
                                </td>
                                <td style="font-weight:bold; font-size:17px;">
                                    {{ number_format($totalPrice) }}đ
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Ẩn input gửi dữ liệu sản phẩm --}}
            @foreach($mergedItems as $item)
                <input type="hidden" name="products[{{ $item['name'].'_'.$item['size_name'] }}][product_id]" value="{{ $item['product_id'] }}">
                <input type="hidden" name="products[{{ $item['name'].'_'.$item['size_name'] }}][name]" value="{{ $item['name'] }}">
                <input type="hidden" name="products[{{ $item['name'].'_'.$item['size_name'] }}][price]" value="{{ $item['price'] }}">
                <input type="hidden" name="products[{{ $item['name'].'_'.$item['size_name'] }}][quantity]" value="{{ $item['quantity'] }}">
                <input type="hidden" name="products[{{ $item['name'].'_'.$item['size_name'] }}][product_size_id]" value="{{ $item['product_size_id'] ?? '' }}">
            @endforeach
        @else
            <p>Giỏ hàng của cửa hàng này trống.</p>
        @endif

        {{-- Upload ảnh chuyển khoản --}}
        <div class="pay-customer">
            <input type="hidden" name="owner_id" value="{{ $ownerModel->user_id ?? 0 }}">
    
            <div class="pay-upload">
                <p>* LƯU Ý:<br>
                    Nội dung chuyển khoản: TÊN + SĐT<br>
                    Chuyển khoản ĐÚNG số tiền ở phần "Tổng tiền"<br>
                    Sau khi hoàn tất, chụp lại màn hình giao dịch và gửi ảnh bên dưới
                </p><br>
                <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png"><br><br>
            </div>
    
            <div class="pay-btn" style="margin-bottom: 100px; text-align: center;">
                <button type="submit" class="order-football-btn">Xác nhận mua hàng</button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chuyển trang khi chọn offline
    const offlineRadio = document.querySelector('input[name="payment_method"][value="offline"]');
    offlineRadio.addEventListener('change', function() {
        if (this.checked) {
            window.location.href = "{{ route('thanh-toan-gio-hang') }}";
        }
    });
});
</script>

@endsection
