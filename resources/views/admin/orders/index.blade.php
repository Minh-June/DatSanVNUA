@extends('layouts.admin')

@section('title', 'Quản lý khách đặt sân thể thao')

@section('content')
    <!-- Hiển thị thông báo thành công -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <!-- Hiển thị thông báo lỗi -->
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <h3>Danh sách khách đặt sân thể thao</h3>

    <!-- Begin: Date Filter -->
    <div class="admin-time">
        <form method="GET" action="{{ route('quan-ly-khach-hang') }}">
            <label for="selected_date">Chọn ngày:</label>
            <input type="date" id="selected_date" name="selected_date" value="{{ request('selected_date') }}" required>
            <button class="admin-time-btn" type="submit" name="filter_date">Tìm kiếm</button>
        </form>
    </div>        
    <!-- End: Date Filter -->

    <!-- Begin: Display Orders -->
    @if($orders->count() > 0)
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>Họ và tên</th>
                <th>Số điện thoại</th>
                <th>Tên sân</th>
                <th>Số sân</th>
                <th>Ngày</th>
                <th>Thời gian</th>
                <th>Thành tiền</th>
                <th>Ghi chú</th>
                <th>Ảnh thanh toán</th>
                <th>Cập nhật</th>
                <th>Xóa</th>
                <th>Trạng thái</th>
            </tr>
            @foreach($orders as $key => $order)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ $order->phone }}</td>
                    <td>{{ $order->san ? $order->san->tensan : 'Không xác định' }}</td>
                    <td>{{ $order->san ? $order->san->sosan : 'Không xác định' }}</td>
                    <td>{{ $order->date }}</td>
                    <td>
                        @foreach (explode(',', $order->time) as $timeSlot)
                            {{ $timeSlot }} <br>
                        @endforeach
                    </td>
                    <td>{{ $order->price }} VND</td>
                    <td>{{ $order->notes }}</td>
                    <td>
                        @if($order->image)
                            @php
                                $images = json_decode($order->image);
                            @endphp
                            @foreach ($images as $img)
                                <img src="{{ asset('storage/' . $img) }}" alt="Hình ảnh" class="admin-image">
                            @endforeach
                        @endif
                    </td>
                    <td>
                        <form method="GET" action="{{ route('orders.edit', $order->order_id) }}">
                            <button type="submit">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('orders.delete', $order->order_id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Xóa</button>
                        </form>
                    </td>                                    
                    <td>
                        <form method='POST' action='{{ route('orders.updateStatus', $order->order_id) }}'>
                            @csrf
                            <select name='status'>
                                <option value='choxacnhan' {{ $order->status == 'choxacnhan' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value='xacnhan' {{ $order->status == 'xacnhan' ? 'selected' : '' }}>Xác nhận đơn</option>
                                <option value='huydon' {{ $order->status == 'huydon' ? 'selected' : '' }}>Hủy đơn</option>
                            </select>
                            <button type='submit'>Cập nhật</button>
                        </form>
                    </td>                                  
                </tr>
            @endforeach
        </table>
    @else
        <p>Không có kết quả</p>
    @endif
    <!-- End: Display Orders -->
@endsection
