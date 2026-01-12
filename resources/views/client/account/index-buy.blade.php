@extends('layouts.client.account')

@section('title', 'Lịch sử mua hàng')

@section('content')
    <h2>Danh sách đơn mua hàng</h2>

    <!-- Begin: Date Filter -->
    <div class="admin-search">
        <form method="GET" action="{{ route('lich-su-mua-hang') }}">
            <label for="date">Ngày:</label>
            <input type="date" id="date" name="date" value="{{ $selectedDate }}">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>
    <!-- End: Date Filter -->

    @if($groupedOrders->count() > 0)
        <table id="ListCustomers">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Ngày mua</th>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Địa chỉ</th>
                    <th>Ghi chú</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
            @php $index = 1; @endphp

            @foreach($groupedOrders as $groupDate => $orders)
                @php
                    $allDetails = $orders->flatMap(fn($o) => $o->orderDetails);
                    $rowspan = $allDetails->count();
                    $isFirstGroup = true;
                @endphp

                @foreach($orders as $order)
                    @php
                        $details = $order->orderDetails;
                        $statusRowspan = $details->count();
                        $isFirstDetail = true;
                    @endphp

                    @foreach($details as $detail)
                        <tr>
                            {{-- 1. STT --}}
                            @if($isFirstGroup)
                                <td rowspan="{{ $rowspan }}">{{ $index++ }}</td>
                            @endif

                            {{-- 2. Ngày mua --}}
                            @if($isFirstGroup)
                                <td rowspan="{{ $rowspan }}">
                                    {{ \Carbon\Carbon::parse($groupDate)->format('d/m/Y') }}<br>
                                    {{ \Carbon\Carbon::parse($order->date)->format('H:i') }}
                                </td>
                            @endif

                            {{-- 3. Sản phẩm --}}
                            <td class="left-align">
                                @php
                                    $name = $detail->product->name ?? 'Không xác định';
                                    $size = $detail->size?->name ? 'Size '.$detail->size->name : '';
                                    $chunks = array_chunk(explode(' ', $name), 3);
                                @endphp
                                @foreach($chunks as $chunk)
                                    {{ implode(' ', $chunk) }}<br>
                                @endforeach
                                @if($size)
                                    - {{ $size }}
                                @endif
                            </td>

                            {{-- 4. Đơn giá --}}
                            <td>{{ number_format($detail->price, 0, ',', '.') }}đ</td>

                            {{-- 5. Số lượng --}}
                            <td>{{ $detail->quantity }}</td>

                            {{-- 6. Thành tiền --}}
                            <td>{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}đ</td>

                            {{-- 7. Địa chỉ --}}
                            @if($isFirstGroup)
                                <td rowspan="{{ $rowspan }}">
                                    @php
                                        $address = trim($order->address ?? '');
                                        $chunks = array_chunk(explode(' ', $address), 2);
                                    @endphp
                                    @if(empty($address))
                                        Không có
                                    @else
                                        @foreach($chunks as $chunk)
                                            {{ implode(' ', $chunk) }}<br>
                                        @endforeach
                                    @endif
                                </td>
                            @endif

                            {{-- 8. Ghi chú --}}
                            @if($isFirstGroup)
                                <td rowspan="{{ $rowspan }}">
                                    @php
                                        $notes = trim($order->notes ?? '');
                                        $chunks = array_chunk(explode(' ', $notes), 2);
                                    @endphp
                                    @if(empty($notes))
                                        Không có
                                    @else
                                        @foreach($chunks as $chunk)
                                            {{ implode(' ', $chunk) }}<br>
                                        @endforeach
                                    @endif
                                </td>
                            @endif

                            {{-- 9. Thanh toán --}}
                            @if($isFirstGroup)
                                <td rowspan="{{ $rowspan }}">
                                    @php $images = json_decode($order->image) ?? []; @endphp
                                    @if(count($images))
                                        @foreach($images as $img)
                                            <img class="order-img" src="{{ asset('storage/' . $img) }}"
                                                onclick="showImage('{{ asset('storage/' . $img) }}')">
                                        @endforeach
                                    @else
                                        Thanh toán<br>khi nhận<br>hàng
                                    @endif
                                </td>
                            @endif

                            {{-- 10. Trạng thái --}}
                            @if($isFirstDetail)
                                <td rowspan="{{ $statusRowspan }}">
                                    @switch($order->status)
                                        @case(0)
                                            <span class="status status-pending">Chờ xác nhận</span>
                                            @break
                                        @case(1)
                                            <span class="status status-confirmed">Đã giao hàng</span>
                                            @break
                                        @case(2)
                                            <span class="status status-cancelled">Đơn đã bị hủy</span>
                                            @break
                                        @case(3)
                                            <span class="status status-deposit">Đã đặt cọc</span>
                                            @break
                                        @default
                                            <span class="status status-unknown">Không xác định</span>
                                    @endswitch
                                </td>
                            @endif
                        </tr>

                        @php
                            $isFirstDetail = false;
                            $isFirstGroup = false;
                        @endphp
                    @endforeach
                @endforeach
            @endforeach
            </tbody>
        </table>

    @else
        <h2 style="font-weight: normal; font-size: 18px;">Bạn chưa có đơn mua hàng nào</h2>
    @endif
@endsection
