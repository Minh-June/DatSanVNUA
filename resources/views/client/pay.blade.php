@extends('layouts.client.client')

@section('title', 'Thanh toán')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

<div id="content" class="order-section">
    <h2 class="order-heading">THANH TOÁN</h2>

    <div class="pay-content">
        <div class="pay-information">
            <div class="bank-account">Tài khoản ngân hàng</div>
            <div class="bank-account">Tên tài khoản: Nguyễn Hữu Quang Minh</div>
            <div class="bank-account">Số tài khoản: 1903 6786 8800 12</div>
            <div class="bank-account">Ngân hàng: Techcombank</div>
        </div>
        <div class="pay-information">
            <div class="bank-qr">
                Mã QR <br>
                <img class="bank-qr-img" src="{{ asset('image/qr/qr.jpg') }}" alt="Mã QR">
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <div class="pay-customer">
        <h3>Thông tin đơn đặt sân</h3><br>

        <table id="ListCustomers">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Ngày đặt</th>
                    <th>Họ và tên</th>
                    <th>SĐT</th>
                    <th>Tên sân</th>
                    <th>Thời gian thuê</th>
                    <th>Giá từng khung giờ</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                @php $totalAmount = 0; @endphp
                @forelse ($orders as $index => $order)
                    @php $totalAmount += $order['price']; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}</td>
                        @if ($index === 0)
                            <td rowspan="{{ count($orders) }}">{{ $order['name'] }}</td>
                            <td rowspan="{{ count($orders) }}">{{ $order['phone'] }}</td>
                        @endif
                        <td>{{ $order['yard_name'] }}</td>
                        <td>
                            @foreach ($order['times'] as $time)
                                {{ $time }}<br>
                            @endforeach
                        </td>
                        <td>
                            @if(!empty($order['price_per_slot']) && is_array($order['price_per_slot']))
                            @foreach($order['price_per_slot'] as $price)
                            {{ number_format($price) }} VND<br>
                            @endforeach
                            @else
                            Không có dữ liệu
                            @endif
                        </td>
                        <td>{{ $order['notes'] ?? 'Không có ghi chú' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8">Không có đơn đặt sân nào.</td></tr>
                @endforelse
            </tbody>
            @if(count($orders) > 0)
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align: right;"><strong>Tổng tiền:</strong></td>
                    <td colspan="2"><strong>{{ number_format($totalAmount) }} VND</strong></td>
                </tr>
            </tfoot>
            @endif
        </table>

        @if (count($orders) > 0)
        <div class="pay-upload">
            <p>* LƯU Ý: Nếu bạn muốn thanh toán trước<br><br>
                Chuyển khoản ĐÚNG số tiền ở phần "Tổng tiền"<br><br>
                Nội dung chuyển khoản: TÊN + SĐT<br><br>
                Sau khi hoàn tất, chụp lại màn hình giao dịch và gửi ảnh bên dưới.</p>

            <form action="{{ route('pay.upload') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" name="images[]" multiple accept="image/*"><br><br>
                <div class="pay-btn">
                    <button type="submit" class="order-football-btn">Xác nhận đặt sân</button>
                </div>
            </form>
        </div>
        @endif
    </div>

    <div class="clear"></div>
</div>
@endsection
