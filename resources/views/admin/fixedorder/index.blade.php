@extends('layouts.admin')

@section('title','Quản lý đơn thuê cố định theo tháng')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif
@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

@php
    $currentUser = auth()->user();
    $today = now()->toDateString();
    $statusOptions = [
        0 => 'Chờ xác nhận',
        1 => 'Xác nhận',
        2 => 'Hủy',
        3 => 'Đã đặt cọc'
    ];
@endphp

<h2>Danh sách đơn cố định</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <form method="GET" action="{{ route('quan-ly-don-dat-san-co-dinh') }}">
            <label for="selected_date">Ngày:</label>
            <input type="date" id="selected_date" name="selected_date"
                   value="{{ request('selected_date', now()->toDateString()) }}">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>

    <div class="admin-add-btn">
        @if(auth()->user()->role != 3)
            <a class="update-btn" style="margin-left:10px;" href="{{ route('trang-chu') }}">Thêm đơn đặt sân cố định</a>
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
        @foreach($orders as $order)
            @php
                $orderDate = \Carbon\Carbon::parse($order->date)->toDateString();
                $endDate = \Carbon\Carbon::parse($order->to_date)->toDateString();

                $isOwner = $order->yard && $order->yard->user_id == $currentUser->user_id;
                $isStaff = $order->yard && $order->yard->user_id == $currentUser->manager_id;

                // Quyền chỉnh sửa: Admin, Chủ sân, Nhân viên nhưng chỉ với đơn chưa quá hạn
                $canEdit = ($currentUser->role == 0) ||
                           ($currentUser->role == 2 && $isOwner) ||
                           ($currentUser->role == 3 && $isStaff && $orderDate >= $today);
            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>

                {{-- Ngày đặt --}}
                <td>
                    {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
                    {{ \Carbon\Carbon::parse($order->date)->format('H:i') }}
                </td>

                {{-- Họ tên --}}
                <td class="left-align">{{ $order->user->fullname ?? 'Khách vãng lai' }}</td>

                {{-- SĐT --}}
                <td>{{ $order->user->phonenb ?? '' }}</td>

                {{-- Tổng tiền --}}
                <td>{{ number_format($order->price, 0, ',', '.') }}đ</td>

                {{-- Thanh toán --}}
                <td>Thanh toán<br>tại sân</td>

                {{-- Chi tiết --}}
                <td>
                    <a href="{{ route('cap-nhat-don-dat-san-co-dinh', $order->month_rent_id) }}">Chi tiết</a>
                </td>

                {{-- Tùy chọn --}}
                @if($canEdit)
                    <td>
                        <form method="POST" action="{{ route('cap-nhat-trang-thai-don-dat-san-co-dinh', $order->month_rent_id) }}">
                            @csrf
                            <select name="status">
                                @foreach($statusOptions as $val => $text)
                                    <option value="{{ $val }}" {{ $order->status == $val ? 'selected' : '' }}>
                                        {{ $text }}
                                    </option>
                                @endforeach
                            </select><br>
                            <button class="update-btn" type="submit">Cập nhật</button>
                        </form>
                    </td>

                    @if($currentUser->role != 3)
                        <td>
                            <form method="POST"
                                  action="{{ route('xoa-don-dat-san-co-dinh', $order->month_rent_id) }}"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?')">
                                @csrf
                                @method('DELETE')
                                <button class="delete-btn" type="submit">Xóa</button>
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
<p style="text-align:center; margin-top:20px;">
    Hiện tại chưa có đơn thuê cố định theo tháng nào
</p>
@endif

@endsection
