@extends('layouts.client.client')

@section('title', 'Hợp đồng')

@section('content')
@if (count(session('orders', [])) === 0)
    <script>
        alert("Vui lòng quay về trang chủ để đặt sân !");
        window.location.href = "{{ route('trang-chu') }}";
    </script>
@endif

<div id="content" class="order-section">
    <h2 class="order-heading">Xác nhận thông tin đặt sân</h2>

    <div class="order-successfully">
        <div class="order-successfully-infor">
            <h3 class="order-successfully-header">Hợp đồng đặt sân</h3>

            <h4>Điều 1: Nội dung hợp đồng</h4>
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
                        <th>Ghi chú</th>
                        <th>Thành tiền</th>
                        <th>Tùy chọn</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $groupedOrders = collect(session('orders', []))->groupBy(fn($order) => $order['date'] . '-' . $order['yard_name']);
                    $stt = 1;
                @endphp

                @foreach ($groupedOrders as $group)
                    @php
                        $first = $group->first();
                        $totalPrice = $group->sum('price');
                    @endphp
                    <tr>
                        <td rowspan="{{ $group->count() }}">{{ $stt++ }}</td>
                        <td rowspan="{{ $group->count() }}">{{ \Carbon\Carbon::parse($first['date'])->format('d/m/Y') }}</td>
                        <td rowspan="{{ $group->count() }}">{{ $first['name'] }}</td>
                        <td rowspan="{{ $group->count() }}">{{ $first['phone'] }}</td>
                        <td rowspan="{{ $group->count() }}">{{ $first['yard_name'] }}</td>

                        {{-- Cột đầu tiên: thời gian thuê, ghi chú, thành tiền, thao tác --}}
                        <td>
                            @foreach ($first['times'] as $time)
                                <div>{{ $time }}</div>
                            @endforeach
                        </td>
                        <td>{{ $first['notes'] ?? 'Không có' }}</td>
                        <td>{{ number_format($first['price']) }}đ</td>
                        <td>
                            <form action="{{ route('xoa-don-tam-thoi') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn này?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="index" value="{{ array_search($first, session('orders')) }}">
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    </tr>

                    {{-- Các dòng còn lại (cùng ngày + sân, khác thời gian) --}}
                    @foreach ($group->slice(1) as $order)
                        <tr>
                            <td>
                                @foreach ($order['times'] as $time)
                                    <div>{{ $time }}</div>
                                @endforeach
                            </td>
                            <td>{{ $order['notes'] ?? 'Không có' }}</td>
                            <td>{{ number_format($order['price']) }}đ</td>
                            <td>
                                <form action="{{ route('xoa-don-tam-thoi') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn này?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="index" value="{{ array_search($order, session('orders')) }}">
                                    <button type="submit" class="delete-btn">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>

            <h4>Điều 2: Thanh toán</h4>
            <p>Bên A cam kết thanh toán phí dịch vụ đặt lịch theo thỏa thuận giữa hai bên.</p>

            <h4>Điều 3: Điều khoản chung</h4>
            <p>Cả hai bên cam kết thực hiện đúng và đầy đủ các điều khoản trong hợp đồng này.</p>
            <p>Hợp đồng có giá trị từ ngày ký và có thể được điều chỉnh hoặc chấm dứt khi hai bên đồng ý.</p>

            <h4>Điều 4: Ký và xác nhận</h4>
            <p class="order-successfully-day">
                Hà Nội, ngày {{ date('d') }} tháng {{ date('m') }} năm {{ date('Y') }}
            </p>
            <div class="signature">
                <div class="signature-left">
                    <p>Bên A</p>
                    <p>{{ session('orders.0.name') }}</p>
                </div>
                <div class="signature-right">
                    <p>Bên B</p>
                    <p>Group 48</p>
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
