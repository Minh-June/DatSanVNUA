@extends('layouts.client.account')

@section('title', 'Lịch sử đặt sân')

@section('content') 
    <h2>Danh sách đơn đặt sân</h2>  
    
    <!-- Begin: Date Filter -->
    <div class="admin-search">
        <form method="GET" action="{{ route('thong-tin-tai-khoan') }}">
            <label for="date">Ngày:</label>
            <input type="date" id="date" name="date" value="{{ request('date', date('Y-m-d')) }}">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>        
    <!-- End: Date Filter -->

    <!-- Begin: Display Orders -->
    @if($orders->count() > 0)
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
                    <th>Ảnh thanh toán</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @php $index = 1; @endphp
                @foreach($orders as $order)
                    @php
                        $groupedDetails = $order->groupedDetails ?? collect();
                        $rowspan = $groupedDetails->count();
                        $isFirstGroup = true;
                    @endphp

                    @foreach($groupedDetails as $group)
                        @php
                            $firstDetail = $group->first();
                            $timeString = $group->pluck('time')->implode('<br>');
                        @endphp

                        <tr>
                            @if($isFirstGroup)
                                <td rowspan="{{ $rowspan }}">{{ $index++ }}</td>
                                <td rowspan="{{ $rowspan }}">
                                    {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
                                    {{ \Carbon\Carbon::parse($order->date)->format('H:i') }}
                                </td>
                            @endif

                            @if($loop->first)
                                <td rowspan="{{ $rowspan }}">
                                    {{ \Carbon\Carbon::parse($firstDetail->date)->format('d/m/Y') }}
                                </td>
                            @endif

                            <td>{{ $firstDetail->yard->type->name ?? 'Không xác định' }}</td>
                            <td>{{ $firstDetail->yard->name ?? 'Không xác định' }}</td>
                            <td>{!! $timeString !!}</td>

                            @if($isFirstGroup)
                                <td rowspan="{{ $rowspan }}">
                                    {{ number_format($order->orderDetails->sum('price'), 0, ',', '.') }}đ
                                </td>
                                <td rowspan="{{ $rowspan }}">{{ $firstDetail->notes ?? 'Không có' }}</td>
                                <td rowspan="{{ $rowspan }}">
                                    @php $images = json_decode($order->image) ?? []; @endphp
                                    @if(count($images))
                                        @foreach($images as $img)
                                            <img src="{{ asset('storage/' . $img) }}" style="width:100px; height:200px;" onclick="showImage('{{ asset('storage/' . $img) }}')">
                                        @endforeach
                                    @else
                                        Không có ảnh
                                    @endif
                                </td>
                                <td rowspan="{{ $rowspan }}">
                                    @switch($order->status)
                                        @case(0) Chờ xác nhận @break
                                        @case(1) Đã xác nhận @break
                                        @case(2) Đơn đã bị hủy @break
                                        @default Không xác định
                                    @endswitch
                                </td>
                            @endif
                        </tr>

                        @php $isFirstGroup = false; @endphp
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @else
        <h2 style="font-weight: normal; font-size: 18px;">Hiện chưa có đơn đặt sân nào</h2>
    @endif
    <!-- End: Display Orders -->
@endsection
