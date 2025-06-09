@extends('layouts.admin')

@section('title', 'Quản lý đơn đặt sân thể thao')

@section('content')
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <h3>Danh sách đơn sân thể thao</h3>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="{{ route('quan-ly-don-dat-san') }}">
                <label for="selected_date">Chọn ngày:</label>
                <input type="date" id="selected_date" name="selected_date" value="{{ request('selected_date') }}">
                <button class="admin-search-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a href="{{ route('them-don-dat-san') }}">Thêm đơn đặt sân</a>
        </div>
    </div>

    @if($orders->count() > 0)
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>Ngày tạo</th>
                <th>Họ và tên</th>
                <th>SĐT</th>
                <!-- <th>Tên sân</th>
                <th>Ngày thuê</th>
                <th>Thời gian</th>
                <th>Ghi chú</th> -->
                <th>Thành tiền</th>
                <th>Ảnh thanh toán</th>
                <th>Thông tin</th>
                <th colspan="2">Tùy chọn</th>
            </tr>

            @foreach($orders as $key => $order)
                @php
                    $rowspan = $order->groupedDetails->count();
                @endphp

                @foreach($order->groupedDetails as $index => $detail)
                    <tr>
                        @if($index === 0)
                            <td rowspan="{{ $rowspan }}">{{ $key + 1 }}</td>
                            <td rowspan="{{ $rowspan }}">                
                                {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
                                {{ \Carbon\Carbon::parse($order->date)->format('H:i') }}
                            </td>
                            <td rowspan="{{ $rowspan }}">{{ $order->name }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $order->phone }}</td>
                        @endif

                        <!-- <td>{{ $detail['yard']->name ?? 'Không xác định' }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail['date'])->format('d/m/Y') }}</td>
                        <td>{{ $detail['times'] }}</td>
                        <td>{{ $detail['notes'] ?: 'Không có' }}</td> -->

                        @if($index === 0)
                            <td rowspan="{{ $rowspan }}">
                                {{ number_format($order->orderDetails->sum('price'), 0, ',', '.') }} VND
                            </td>
                        @endif

                        @if($index === 0)
                            <td rowspan="{{ $rowspan }}">
                                @php $images = json_decode($order->image); @endphp
                                @if ($images && count($images) > 0)
                                    @foreach ($images as $img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="Ảnh" style="width:100px; height:200px; cursor:pointer;" onclick="showImage(this.src)">
                                    @endforeach
                                @else
                                    Không có
                                @endif
                            </td>
                            
                            <td rowspan="{{ $rowspan }}">
                                <a href="{{ route('cap-nhat-don-dat-san', $order->order_id) }}">Xem chi tiết</a>
                            </td>

                            <td rowspan="{{ $rowspan }}">
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

                            <td rowspan="{{ $rowspan }}">
                                <form method="POST" action="{{ route('xoa-don-dat-san', $order->order_id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="update-btn">Xóa</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </table>
    @else
        <p>Không có kết quả</p>
    @endif
@endsection

