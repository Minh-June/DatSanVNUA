@extends('layouts.client.account')

@section('title', 'Lịch sử đặt sân')

@section('content')
<h2>Danh sách đơn đặt sân</h2>

<!-- Filter theo ngày -->
<div class="admin-top-bar">
    <div class="admin-search">
        <form method="GET" action="{{ route('thong-tin-tai-khoan') }}">
            <label for="date">Ngày:</label>
            <input type="date" id="date" name="date" value="{{ $selectedDate }}">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>

    <div class="admin-add-btn"></div>
</div>

@if($groupedOrders->count() > 0)
<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Ngày đặt</th>
            <th>Ngày thuê</th>
            <th>Loại sân</th>
            <th>Tên sân</th>
            <th>Khung giờ</th>
            <th>Thành tiền</th>
            <th>Ghi chú</th>
            <th>Thanh toán</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
    @php $index = 1; @endphp

    @foreach($groupedOrders as $orderDate => $ordersInDate)
        @php
            $totalRowsInGroup = $ordersInDate->sum(fn($o) => $o->sortedDetails->count());
            $isFirstOrderInGroup = true;
        @endphp

        @foreach($ordersInDate as $order)
            @php
                // Nhóm theo: Ngày thuê + Sân + Ghi chú để gộp dòng ghi chú trùng nhau
                $detailsByYardAndNote = $order->sortedDetails->groupBy(fn($d) => 
                    $d->date . '_' . $d->yard_id . '_' . trim($d->notes)
                );
                $orderRowspan = $order->sortedDetails->count();
                $isFirstRowInOrder = true;
            @endphp

            @foreach($detailsByYardAndNote as $group)
                @php
                    $groupRowspan = $group->count();
                    $isFirstInGroup = true;
                @endphp

                @foreach($group as $detail)
                <tr>
                    {{-- STT + Ngày đặt: Gộp theo toàn bộ nhóm cùng thời điểm --}}
                    @if($isFirstOrderInGroup)
                        <td rowspan="{{ $totalRowsInGroup }}">{{ $index++ }}</td>
                        <td rowspan="{{ $totalRowsInGroup }}">
                            {{ \Carbon\Carbon::parse($orderDate)->format('d/m/Y') }}<br>
                            {{ \Carbon\Carbon::parse($orderDate)->format('H:i') }}
                        </td>
                    @endif

                    {{-- Ngày thuê + Loại sân + Tên sân: Gộp theo nhóm trùng Ghi chú --}}
                    @if($isFirstInGroup)
                        <td rowspan="{{ $groupRowspan }}">{{ \Carbon\Carbon::parse($detail->date)->format('d/m/Y') }}</td>
                        <td rowspan="{{ $groupRowspan }}">{{ $detail->yard->type->name }}</td>
                        <td rowspan="{{ $groupRowspan }}">{{ $detail->yard->name }}</td>
                    @endif

                    {{-- Khung giờ và Thành tiền: Luôn hiện theo từng dòng để rõ ràng --}}
                    <td>{{ $detail->time }}</td>
                    <td>{{ number_format($detail->price, 0, ',', '.') }}đ</td>

                    {{-- Ghi chú: Gộp nếu trùng nhau trong cùng một sân --}}
                    @if($isFirstInGroup)
                        <td rowspan="{{ $groupRowspan }}">
                            @php
                                $words = $detail->notes
                                    ? preg_split('/\s+/', $detail->notes)
                                    : ['Không', 'có'];

                                // mỗi dòng 6–7 từ cho dễ đọc
                                $chunks = array_chunk($words, 4);
                            @endphp

                            @foreach($chunks as $chunk)
                                {{ implode(' ', $chunk) }}<br>
                            @endforeach
                        </td>
                    @endif

                    {{-- Cột Thanh toán: Gộp theo toàn bộ nhóm đặt --}}
                    @if($isFirstOrderInGroup)
                        <td rowspan="{{ $totalRowsInGroup }}" class="payment-cell">
                            @php
                                $allImages = $ordersInDate->pluck('image')
                                    ->filter(fn($img) => $img && $img !== 'Thanh toán trực tiếp')
                                    ->flatMap(fn($img) => json_decode($img, true) ?? []);
                            @endphp
                            @if($allImages->count())
                                @foreach($allImages as $img)
                                    <img src="{{ asset('storage/' . $img) }}" class="order-img" onclick="showImage('{{ asset('storage/' . $img) }}')">
                                @endforeach
                            @else
                                <span>Thanh toán<br>tại sân</span>
                            @endif
                        </td>
                    @endif

                    {{-- Trạng thái: Gộp theo từng Order --}}
                    @if($isFirstRowInOrder)
                        <td rowspan="{{ $orderRowspan }}">
                            @switch($order->status)
                                @case(\App\Models\Order::STATUS_PENDING) <span class="status status-pending">Chờ xác nhận</span> @break
                                @case(\App\Models\Order::STATUS_CONFIRMED) <span class="status status-confirmed">Đã xác nhận</span> @break
                                @case(\App\Models\Order::STATUS_CANCELLED) <span class="status status-cancelled">Đơn đã bị hủy</span> @break
                                @case(\App\Models\Order::STATUS_DEPOSIT) <span class="status status-deposit">Đã đặt cọc</span> @break
                            @endswitch
                        </td>
                    @endif
                </tr>
                @php 
                    $isFirstOrderInGroup = false; 
                    $isFirstRowInOrder = false;
                    $isFirstInGroup = false;
                @endphp
                @endforeach
            @endforeach
        @endforeach
    @endforeach
    </tbody>
</table>
@else
    <h2 style="font-weight: normal; font-size: 18px;">Bạn chưa có đơn đặt sân nào</h2>
@endif
@endsection
