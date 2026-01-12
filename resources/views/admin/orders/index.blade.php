@extends('layouts.admin')

@section('title', 'Quản lý đơn đặt sân thể thao')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

<h2>Danh sách đơn đặt sân</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <form method="GET" action="{{ route('quan-ly-don-dat-san') }}">
            <input type="hidden" name="yard_name" value="{{ request('yard_name') }}">
            <label for="selected_date">Ngày:</label>
            <input type="date" id="selected_date" name="selected_date" value="{{ request('selected_date', now()->toDateString()) }}">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>

    <div class="admin-add-btn">
        @if(auth()->user()->role != 3)
            <a class="update-btn" style="margin-left:10px;" href="{{ route('trang-chu') }}">Thêm đơn đặt sân</a>
        @endif
    </div>
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
                <th>Thanh toán</th>
                <th>Thông tin</th>
                <th colspan="2">Tùy chọn</th>
            </tr>
        </thead>
        <tbody>
        @php $currentUser = auth()->user(); $today = now()->toDateString(); @endphp
        @foreach($orders as $key => $order)
            @php
                $orderDate = \Carbon\Carbon::parse($order->date)->toDateString();
                $statusOptions = [
                    0 => 'Chờ xác nhận',
                    1 => 'Xác nhận',
                    2 => 'Hủy',
                    3 => 'Đã đặt cọc'
                ];

                $images = json_decode($order->image);

                // Quyền chỉnh sửa: role 0, 2; role 3 nếu là của manager và không phải đơn quá khứ
                $isAdminManaged = $order->orderDetails->contains(fn($d) => $d->yard && $d->yard->user_id == $currentUser->user_id);
                $hasPermission = $order->orderDetails->contains(fn($d) =>
                    ($currentUser->role == 2 && $d->yard->user_id == $currentUser->user_id) ||
                    ($currentUser->role == 3 && $d->yard->user_id == $currentUser->manager_id)
                );

                $canEdit = ($currentUser->role == 0 && $isAdminManaged) ||
                        (in_array($currentUser->role, [2,3]) && $hasPermission && ($currentUser->role != 3 || $orderDate >= $today));
            @endphp

            <tr>
                <td>{{ $key + 1 }}</td>

                <td>
                    {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
                    {{ \Carbon\Carbon::parse($order->date)->format('H:i') }}
                </td>

                <td class="left-align">
                    @foreach(array_chunk(explode(' ', $order->name), 3) as $chunk)
                        {{ implode(' ', $chunk) }}<br>
                    @endforeach
                </td>

                <td>{{ $order->phone }}</td>

                <td>{{ number_format($order->orderDetails->sum('price'), 0, ',', '.') }}đ</td>

                <td>
                    @if($images && count($images))
                        @foreach($images as $img)
                            <img src="{{ asset('storage/' . $img) }}" alt="Ảnh" class="order-img" onclick="showImage(this.src)">
                        @endforeach
                    @else
                        Thanh toán<br>tại sân
                    @endif
                </td>

                <td>
                    <a href="{{ route('cap-nhat-don-dat-san', $order->order_id) }}">Chi tiết</a>
                </td>

                @if($canEdit)
                    <td>
                        <form method="POST" action="{{ route('cap-nhat-trang-thai-don-dat-san', $order->order_id) }}">
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
                            <form method="POST" action="{{ route('xoa-don-dat-san', $order->order_id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    @endif
                @else
                    <td colspan="2">
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
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <h2 style="font-weight: normal; font-size: 18px;">Hiện tại chưa có đơn đặt sân nào</h2>
@endif

@endsection
