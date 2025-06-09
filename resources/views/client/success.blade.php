@extends('layouts.client.client')

@section('title', 'Hợp đồng')

@section('content')
<div id="content" class="order-section">
    <h2 class="order-heading">Xác nhận thông tin đặt sân</h2>

    <div class="order-successfully">
        <div class="order-successfully-infor">
            <h3 class="order-successfully-header">Hợp đồng đặt sân</h3>

            <h3>Điều 1: Nội dung hợp đồng</h3><br>
            <p>Bên A cam kết và thực hiện đặt lịch sân thể thao theo các thông tin sau đây:</p><br>

            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Ngày đặt</th>
                        <th>Họ và tên</th>
                        <th>SĐT</th>
                        <th>Tên sân</th>
                        <th>Thời gian thuê</th>
                        <!-- <th>Giá từng khung giờ</th> -->
                        <th>Ghi chú</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('orders', []) as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ date('d/m/Y', strtotime($order['date'])) }}</td>
                            @if ($index === 0)
                                <td rowspan="{{ count(session('orders')) }}">{{ $order['name'] }}</td>
                                <td rowspan="{{ count(session('orders')) }}">{{ $order['phone'] }}</td>
                            @endif
                            <td>{{ $order['yard_name'] }}</td>
                            <td>
                                @foreach($order['times'] as $time)
                                    <div>{{ $time }}</div>
                                @endforeach
                            </td>
                            <!-- <td>
                                @foreach($order['times'] as $key => $time)
                                    <div>{{ number_format($order['price_per_slot'][$key] ?? 0) }} VND</div>
                                @endforeach
                            </td> -->
                            <td>{{ $order['notes'] ?? 'Không có' }}</td>
                            <td>{{ number_format($order['price']) }} VND</td>
                            <td>
                                <form action="{{ route('xoa-don-tam-thoi') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="index" value="{{ $index }}">
                                    <button type="submit" class="update-btn">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            <h3>Điều 2: Thanh toán</h3><br>
            <p>Bên A cam kết thanh toán phí dịch vụ đặt lịch theo thỏa thuận giữa hai bên.</p><br>

            <h3>Điều 3: Điều khoản chung</h3><br>
            <p>Cả hai bên cam kết thực hiện đúng và đầy đủ các điều khoản trong hợp đồng này.</p>
            <p>Hợp đồng có giá trị từ ngày ký và có thể được điều chỉnh hoặc chấm dứt khi hai bên đồng ý.</p><br>

            <h3>Điều 4: Kí và xác nhận</h3><br>
            <p class="order-successfully-day">Hà Nội, ngày {{ date('d/m/Y') }}</p><br>

            <div class="signature">
                <div class="signature-left">
                    <p>Bên A<br><br> {{ session('orders.0.name') }}</p>
                </div>
                <div class="signature-right">
                    <p>Bên B<br><br> Group 48</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-link">
        <a href="{{ route('thanh-toan') }}" class="order-football-btn">Tiếp tục</a>
    </div>

</div>
<div class="clear"></div>
@endsection
