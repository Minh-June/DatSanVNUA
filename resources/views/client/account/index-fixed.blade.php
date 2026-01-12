@extends('layouts.client.account')

@section('title', 'Lịch sử đặt sân cố định')

@section('content')
<h2>Danh sách đơn đặt sân cố định</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <form method="GET" action="{{ route('client.fixed-orders') }}">
            <label for="date">Ngày:</label>
            <input type="date" id="date" name="date" value="{{ $selectedDate }}">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>
</div>

@if($orders->count())
<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Ngày đặt</th>
            <th>Ngày thuê</th>
            <th>Loại sân</th>
            <th>Thứ</th>
            <th>Tên sân</th>
            <th>Khung giờ</th>
            <th>Thành tiền</th>
            <th>Thanh toán</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
        @php $stt = 1; @endphp
        @foreach($orders as $order)
        <tr>
            <td>{{ $stt++ }}</td>
            {{-- Ngày đặt + giờ --}}
            <td>
                {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
                {{ \Carbon\Carbon::parse($order->date)->format('H:i') }}
            </td>
            {{-- Từ ngày - đến ngày --}}
            <td>
                {{ \Carbon\Carbon::parse($order->from_date)->format('d/m/Y') }}<br>-<br>
                {{ \Carbon\Carbon::parse($order->to_date)->format('d/m/Y') }}
            </td>
            <td>{{ $order->yard->type->name ?? 'Không xác định' }}</td>
            <td>{{ $order->yard->name ?? 'Không xác định' }}</td>
            <td>
                @foreach(explode(',', $order->weekday) as $day)
                    {{ ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'][$day] ?? '' }}<br>
                @endforeach
            </td>
            <td>{{ $order->times }}</td>
            <td>{{ number_format($order->totalPrice, 0, ',', '.') }}đ</td>
            <td>
                @if(!empty($order->image))
                    @foreach(json_decode($order->image) as $img)
                        <img class="order-img" src="{{ asset('storage/' . $img) }}" onclick="showImage('{{ asset('storage/' . $img) }}')">
                    @endforeach
                @else
                    Thanh toán<br>tại sân
                @endif
            </td>
            <td>
                @switch($order->status)
                    @case(\App\Models\Order::STATUS_PENDING)
                        <span class="status status-pending">Chờ xác nhận</span>
                        @break
                    @case(\App\Models\Order::STATUS_CONFIRMED)
                        <span class="status status-confirmed">Đã xác nhận</span>
                        @break
                    @case(\App\Models\Order::STATUS_CANCELLED)
                        <span class="status status-cancelled">Đơn đã hủy</span>
                        @break
                    @case(\App\Models\Order::STATUS_DEPOSIT)
                        <span class="status status-deposit">Đã đặt cọc</span>
                        @break
                    @default
                        <span class="status status-unknown">Không xác định</span>
                @endswitch
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<p style="text-align:center; font-size:16px;">Bạn chưa có đơn đặt sân cố định nào</p>
@endif
@endsection
