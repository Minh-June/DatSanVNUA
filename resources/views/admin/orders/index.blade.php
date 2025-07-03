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
            <a class="update-btn" href="{{ route('trang-chu') }}">Thêm đơn đặt sân</a>
        </div>
    </div>

    @if($orders->count())
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>Ngày tạo</th>
                <th>Họ và tên</th>
                <th>SĐT</th>
                <th>Thành tiền</th>
                <th>Ảnh thanh toán</th>
                <th>Thông tin</th>
                <th>Tùy chọn</th>
                @if (Auth::user()->role == 0)
                    <th>Xóa</th>
                @endif
            </tr>

            @foreach($orders as $key => $order)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
                        {{ \Carbon\Carbon::parse($order->date)->format('H:i') }}
                    </td>
                    <td class="left-align">{{ $order->name }}</td>
                    <td>{{ $order->phone }}</td>
                    <td>
                        {{ number_format($order->orderDetails->sum('price'), 0, ',', '.') }}đ
                    </td>
                    <td>
                        @php $images = json_decode($order->image); @endphp
                        @if ($images && count($images) > 0)
                            @foreach ($images as $img)
                                <img src="{{ asset('storage/' . $img) }}" alt="Ảnh" style="width:100px; height:200px; cursor:pointer;" onclick="showImage(this.src)">
                            @endforeach
                        @else
                            Không có
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('cap-nhat-don-dat-san', $order->order_id) }}">Xem chi tiết</a>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('cap-nhat-trang-thai-don-dat-san', $order->order_id) }}">
                            @csrf
                            <select name="status">
                                <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>Xác nhận</option>
                                <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Hủy</option>
                            </select><br>
                            <button type="submit" class="update-btn">Cập nhật</button>
                        </form>
                    </td>
                    @if (Auth::user()->role == 0)
                        <td>
                            <form method="POST" action="{{ route('xoa-don-dat-san', $order->order_id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </table>
    @else
        <h2 style="font-weight: normal; font-size: 18px;">Không có đơn đặt sân nào</h2>
    @endif
@endsection
