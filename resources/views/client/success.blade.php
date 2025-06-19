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
                        <th>Ngày đặt</th>
                        <th>Loại sân</th>
                        <th>Tên sân</th>
                        <th>Thời gian thuê</th>
                        <th>Giá từng khung giờ</th>
                        <th>Ghi chú</th>
                        <th>Tùy chọn</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $orders = collect(session('orders', []));
                        $groupedByUser = $orders->groupBy(fn($o) => $o['name'] . '-' . $o['phone']);
                        $totalAmount = $orders->sum('price');
                    @endphp

                    @forelse ($groupedByUser as $userGroup)
                        @php
                            $rowspanNamePhone = $userGroup->count();
                            $firstNamePhoneRow = true;
                            $groupedByDate = $userGroup->groupBy('date');
                        @endphp

                        @foreach ($groupedByDate as $date => $dateGroup)
                            @php
                                $rowspanDate = $dateGroup->count();
                                $firstDateRow = true;
                                $groupedByType = $dateGroup->groupBy('type_name');
                            @endphp

                            @foreach ($groupedByType as $type => $typeGroup)
                                @php
                                    $rowspanType = $typeGroup->count();
                                    $firstTypeRow = true;
                                @endphp

                                @foreach ($typeGroup as $index => $order)
                                    <tr>
                                        {{-- Gộp họ tên + SĐT --}}
                                        @if ($firstNamePhoneRow)
                                            <td rowspan="{{ $rowspanNamePhone }}">{{ $order['name'] }}</td>
                                            <td rowspan="{{ $rowspanNamePhone }}">{{ $order['phone'] }}</td>
                                            @php $firstNamePhoneRow = false; @endphp
                                        @endif

                                        {{-- Gộp ngày đặt --}}
                                        @if ($firstDateRow)
                                            <td rowspan="{{ $rowspanDate }}">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                            @php $firstDateRow = false; @endphp
                                        @endif

                                        {{-- Gộp loại sân --}}
                                        @if ($firstTypeRow)
                                            <td rowspan="{{ $rowspanType }}">{{ $type }}</td>
                                            @php $firstTypeRow = false; @endphp
                                        @endif

                                        {{-- Tên sân --}}
                                        <td>{{ $order['yard_name'] }}</td>

                                        {{-- Thời gian thuê --}}
                                        <td>
                                            @foreach ($order['times'] as $time)
                                                {{ $time }}<br>
                                            @endforeach
                                        </td>

                                        {{-- Giá từng khung giờ --}}
                                        <td>
                                            @foreach ($order['price_per_slot'] ?? [] as $price)
                                                {{ number_format($price) }}đ<br>
                                            @endforeach
                                        </td>

                                        {{-- Ghi chú --}}
                                        <td>{{ $order['notes'] ?? 'Không có' }}</td>

                                        {{-- Tùy chọn --}}
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
                        @endforeach
                    @empty
                        <tr><td colspan="9">Không có đơn đặt sân nào.</td></tr>
                    @endforelse
                </tbody>

                @if(count($orders) > 0)
                <tfoot>
                    <tr>
                        <td colspan="6" style="text-align: right;"><strong>Tổng tiền:</strong></td>
                        <td colspan="3"><strong>{{ number_format($totalAmount) }}đ</strong></td>
                    </tr>
                </tfoot>
                @endif
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
