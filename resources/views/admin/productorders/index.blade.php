@extends('layouts.admin')

@section('title', 'Quản lý đơn mua hàng thể thao')

@section('content')
@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif
@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

@php $currentUser = auth()->user(); $today = now()->toDateString(); @endphp

<h2>Danh sách đơn mua hàng</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <form method="GET" action="{{ route('quan-ly-don-mua-hang') }}">
            <label for="selected_date">Ngày:</label>
            <input type="date" id="selected_date" name="selected_date" value="{{ $selectedDate }}">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>

    @if($currentUser->role != 3)
    <div class="admin-add-btn">
        <a class="update-btn" href="{{ route('trang-chu') }}">Thêm đơn mua hàng</a>
    </div>
    @endif
</div>

@if($orders->count())
<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Ngày đặt</th>
            <th>Họ và tên</th>
            <th>SĐT</th>
            <th>Tổng tiền</th>
            <th>Địa chỉ</th>
            <th>Ghi chú</th>
            <th>Thanh toán</th>
            <th>Thông tin</th>
            <th colspan="2">Tùy chọn</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $index => $order)
        @php
            $orderDate = \Carbon\Carbon::parse($order->date)->toDateString();
            $images = $order->image ? json_decode($order->image) : [];
            $statusOptions = [
                0 => 'Chờ xác nhận',
                1 => 'Xác nhận',
                2 => 'Hủy',
                3 => 'Đã đặt cọc'
            ];

            // Quyền chỉnh sửa:
            // Admin (0) và Chủ sân (2) luôn được quyền
            // Nhân viên (3) chỉ thao tác nếu đơn hôm nay trở đi
            $canEdit = ($currentUser->role == 0 || $currentUser->role == 2) ||
                       ($currentUser->role == 3 && $orderDate >= $today);
        @endphp

        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
                {{ \Carbon\Carbon::parse($order->date)->format('H:i') }}
            </td>
            <td class="left-align">
                @foreach(array_chunk(explode(' ', $order->name), 1) as $chunk)
                    {{ implode(' ', $chunk) }}<br>
                @endforeach
            </td>
            <td>{{ $order->phonenb }}</td>
            <td>{{ number_format($order->shop_total_price, 0, ',', '.') }}đ</td>
            <td>
                @php
                    $address = trim($order->address ?? '');
                    $addressChunks = $address ? array_chunk(explode(' ', $address), 2) : [];
                @endphp
                @if($address)
                    @foreach($addressChunks as $chunk)
                        {{ implode(' ', $chunk) }}<br>
                    @endforeach
                @else
                    Không có
                @endif
            </td>
            <td>
                @php
                    $notes = trim($order->notes ?? '');
                    $noteChunks = $notes ? array_chunk(explode(' ', $notes), 2) : [];
                @endphp
                @if($notes)
                    @foreach($noteChunks as $chunk)
                        {{ implode(' ', $chunk) }}<br>
                    @endforeach
                @else
                    Không có
                @endif
            </td>
            <td>
                @if($images && count($images) > 0)
                    @foreach($images as $img)
                        <img src="{{ asset('storage/' . $img) }}" alt="Ảnh" class="order-img" onclick="showImage(this.src)">
                    @endforeach
                @else
                    Thanh toán<br>khi nhận<br>hàng
                @endif
            </td>
            <td>
                <a href="{{ route('cap-nhat-don-mua-hang', $order->product_order_id) }}">Chi tiết</a>
            </td>

            {{-- Tùy chọn --}}
            @if($canEdit)
                <td>
                    <form method="POST" action="{{ route('cap-nhat-trang-thai-don-mua-hang', $order->product_order_id) }}">
                        @csrf
                        <select name="status">
                            @foreach($statusOptions as $val => $text)
                                @if($val != 3 || ($images && count($images)))
                                    <option value="{{ $val }}" {{ $order->status == $val ? 'selected' : '' }}>{{ $text }}</option>
                                @endif
                            @endforeach
                        </select><br>
                        <button type="submit" class="update-btn">Cập nhật</button>
                    </form>
                </td>

                @if($currentUser->role != 3)
                    <td>
                        <form method="POST" action="{{ route('xoa-don-mua-hang', $order->product_order_id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                @endif
            @else
                <td colspan="2">
                    @switch($order->status)
                        @case(0)
                            <span class="status status-pending">Chờ xác nhận</span>
                            @break
                        @case(1)
                            <span class="status status-confirmed">Đã xác nhận</span>
                            @break
                        @case(2)
                            <span class="status status-cancelled">Đơn đã hủy</span>
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
        @endforeach
    </tbody>
</table>
@else
<h2 style="font-weight: normal; font-size: 18px;">Hiện tại chưa có đơn mua hàng nào</h2>
@endif
@endsection
