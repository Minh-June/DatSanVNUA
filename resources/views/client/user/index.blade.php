@extends('layouts.client.user')

@section('title', 'Trung tâm tài khoản')

@section('content') 
                        <h3>Danh sách sân đã đặt</h3>  
                        
                        <!-- Begin: Date Filter -->
                        <div class="admin-time">
                            <form method="GET" action="{{ route('thong-tin-tai-khoan') }}">
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
                                            @switch($order->status)
                                                @case('choxacnhan')
                                                    Chờ xác nhận
                                                    @break
                                                @case('xacnhan')
                                                    Đã xác nhận
                                                    @break
                                                @case('huydon')
                                                    Đơn đã bị hủy
                                                    @break
                                                @default
                                                    Không xác định
                                            @endswitch
                                        </td>                                        
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <p>Không có kết quả</p>
                        @endif
                        <!-- End: Display Orders -->
@endsection