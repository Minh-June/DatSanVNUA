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
            <h2 class="order-successfully-header">Hợp đồng đặt sân</h2>

            <h4>Điều 1: Nội dung hợp đồng</h4>
            <p>Bên A cam kết và thực hiện đặt lịch sân thể thao theo các thông tin sau đây:</p><br>

            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>Họ và tên</th>
                        <th>SĐT</th>
                        <th>Ngày thuê</th>
                        <th>Loại sân</th>
                        <th>Tên sân</th>
                        <th>Thời gian</th>
                        <th>Giá (đ)</th>
                        <th>Ghi chú</th>
                        <th>Tùy chọn</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $orders = collect(session('orders', []));
                    $rows = collect();

                    foreach ($orders as $order) {
                        foreach ($order['times'] as $i => $time) {
                            $rows->push([
                                'name'  => $order['name'],
                                'phone' => $order['phone'],
                                'date'  => $order['date'],
                                'type'  => $order['type_name'],
                                'yard'  => $order['yard_name'],
                                'time'  => $time,
                                'price' => $order['price_per_slot'][$i] ?? 0,
                                'notes' => $order['notes'] ?? 'Không có',
                            ]);
                        }
                    }

                    // Gộp theo Ngày thuê + Tên sân
                    $groups = $rows->groupBy(fn($r) => $r['date'].'_'.$r['yard']);
                    $totalAmount = $rows->sum('price');

                    $globalRowspan = $rows->count();
                    $printedGlobal = false;
                @endphp

                @foreach($groups as $group)
                    @php
                        $rowspan = $group->count();
                        $firstRow = true;
                    @endphp

                    @foreach($group as $row)
                    <tr>
                        @if(!$printedGlobal)
                            <td rowspan="{{ $globalRowspan }}">{{ $row['name'] }}</td>
                            <td rowspan="{{ $globalRowspan }}">{{ $row['phone'] }}</td>
                            @php $printedGlobal = true; @endphp
                        @endif

                        @if($firstRow)
                            <td rowspan="{{ $rowspan }}">
                                {{ \Carbon\Carbon::parse($row['date'])->format('d/m/Y') }}
                            </td>
                            <td rowspan="{{ $rowspan }}">{{ $row['type'] }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $row['yard'] }}</td>
                        @endif

                        <td>{{ $row['time'] }}</td>
                        <td>{{ number_format($row['price']) }}đ</td>
                        <td>
                            @php
                                $words = preg_split('/\s+/', trim(strip_tags($row['notes'])));
                                $chunks = array_chunk($words, 4);
                            @endphp

                            @foreach($chunks as $chunk)
                                {{ implode(' ', $chunk) }}<br>
                            @endforeach
                        </td>
                        <td>
                            <form action="{{ route('xoa-don-tam-thoi') }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn này ?')">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="index" value="{{ array_search($order, session('orders')) }}">
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    </tr>

                    @php $firstRow = false; @endphp
                    @endforeach
                @endforeach

                @if($rows->count())
                <tr>
                    <td colspan="6" style="text-align:right"><b>Tổng tiền</b></td>
                    <td colspan="3"><b>{{ number_format($totalAmount) }}đ</b></td>
                </tr>
                @endif
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
                    @foreach ($owners as $name)
                        <p>{{ $name }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="footer-link" style="margin:40px 0 50px 0;">
        <a href="{{ route('thanh-toan') }}" class="order-football-btn">Tiến hành thanh toán</a>
    </div>

</div>
<div class="clear"></div>
@endsection
