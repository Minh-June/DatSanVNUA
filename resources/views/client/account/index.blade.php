@extends('layouts.client.account')

@section('title', 'Lịch sử đặt sân')

@section('content') 
    <h3>Danh sách đơn đặt sân</h3>  
    
    <!-- Begin: Date Filter -->
    <div class="admin-search">
        <form method="GET" action="{{ route('thong-tin-tai-khoan') }}">
            <label for="date">Chọn ngày:</label>
            <input type="date" id="date" name="date" value="{{ request('date', date('Y-m-d')) }}">
<<<<<<< HEAD
            <button class="admin-search-btn" type="submit">Tìm kiếm</button>
=======
            <button class="update-btn" type="submit">Tìm kiếm</button>
>>>>>>> 80d6e7c (Cập nhật giao diện)
        </form>
    </div>        
    <!-- End: Date Filter -->

    <!-- Begin: Display Orders -->
    @if($orders->count() > 0)
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>Ngày đặt</th>
                <!-- <th>Họ và tên</th>
                <th>Số điện thoại</th> -->
                <th>Ngày thuê</th>
                <th>Tên sân</th>
                <th>Thời gian</th>
                <!-- <th>Giá từng khung giờ</th> -->
                <th>Thành tiền</th>
                <th>Ghi chú</th>
                <th>Ảnh thanh toán</th>
                <th>Trạng thái</th>
            </tr>
            @php $index = 1; @endphp
            @foreach($orders as $order)
                @php
                    $totalDetailsCount = $order->orderDetails->count();
                    $groupedDetails = $order->orderDetails->groupBy(function($item) {
                        return $item->yard_id . '_' . $item->date;
                    });
                    $orderRowspan = $totalDetailsCount;
                @endphp

                @foreach($groupedDetails as $group)
                    @php
                        $firstDetail = $group->first();
                        $timeString = $group->pluck('time')->implode('<br>');
                    @endphp

                    <tr>
                        @if ($loop->parent->first)
                            <td rowspan="{{ $orderRowspan }}">{{ $index++ }}</td>
                            <td rowspan="{{ $orderRowspan }}">
                                {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
                                {{ \Carbon\Carbon::parse($order->date)->format('H:i') }}
                            </td>
                        @endif

                        <td>{{ \Carbon\Carbon::parse($firstDetail->date)->format('d/m/Y') }}</td>
                        <td>{{ $firstDetail->yard ? $firstDetail->yard->name : 'Không xác định' }}</td>
                        <td>{!! $timeString !!}</td>

                        @if ($loop->parent->first)
                            <td rowspan="{{ $orderRowspan }}">
<<<<<<< HEAD
                                {{ number_format($order->orderDetails->sum('price'), 0, ',', '.') }} VND
                            </td>
                            <td rowspan="{{ $orderRowspan }}">{{ $firstDetail->notes ?? 'Không có ghi chú' }}</td>
=======
                                {{ number_format($order->orderDetails->sum('price'), 0, ',', '.') }}đ
                            </td>
                            <td rowspan="{{ $orderRowspan }}">{{ $firstDetail->notes ?? 'Không có' }}</td>
>>>>>>> 80d6e7c (Cập nhật giao diện)
                            <td rowspan="{{ $orderRowspan }}">
                                @php
                                    $images = json_decode($order->image) ?: [];
                                @endphp
                                @if(count($images) > 0)
                                    @foreach($images as $img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="Ảnh thanh toán" style="width:100px; height:200px; cursor: pointer;" onclick="showImage('{{ asset('storage/' . $img) }}')">
                                    @endforeach
                                @else
                                    Không có ảnh
                                @endif
                            </td>
                            <td rowspan="{{ $orderRowspan }}">
                                @switch($order->status)
                                    @case(0) Chờ xác nhận @break
                                    @case(1) Đã xác nhận @break
                                    @case(2) Đơn đã bị hủy @break
                                    @default Không xác định
                                @endswitch
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </table>
    @else
<<<<<<< HEAD
        <h3 style="font-weight: normal; font-size: 17px;">Hiện tại bạn chưa có đơn đặt sân nào.</h3>
=======
        <h3 style="font-weight: normal; font-size: 18px;">Hiện tại bạn chưa có đơn đặt sân nào</h3>
>>>>>>> 80d6e7c (Cập nhật giao diện)
    @endif
    <!-- End: Display Orders -->
@endsection
