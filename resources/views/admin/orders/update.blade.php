@extends('layouts.admin')

@section('title', 'Cập nhật thông tin đơn đặt sân')

@section('content')
    @if(session('price_change_message'))
        <script>
            alert("{{ session('price_change_message') }}");
        </script>
    @endif

    <!-- Hiển thị thông báo lỗi -->
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif
    
    <h3>Chi tiết đơn đặt sân</h3>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            <a href="{{ route('quan-ly-don-dat-san') }}">Quay lại danh sách</a>
        </div>
    </div>

    <table id="ListCustomers">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sân</th>
                <th>Ngày thuê</th>
                <th>Khung giờ</th>
                <th>Giá</th>
                <th>Ghi chú</th>
                <th colspan="2">Tùy chọn</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderDetails as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->yard->name ?? 'Không xác định' }}</td>
                    <td>{{ \Carbon\Carbon::parse($detail->date)->format('d/m/Y') }}</td>
                    <td>{{ optional($detail->time)->time ?? $detail->time }}</td>
                    <td>{{ number_format($detail->price, 0, ',', '.') }} VND</td>
                    <td>{{ $detail->notes ?: 'Không có' }}</td>
                    <td>
                        <form action="{{ route('cap-nhat-chi-tiet-don', $detail->order_detail_id) }}" method="GET" style="display:inline;">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('xoa-chi-tiet-don', $detail->order_detail_id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa chi tiết này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="update-btn">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right;"><strong>Tổng tiền:</strong></td>
                <td colspan="2"><strong>{{ number_format($totalPrice, 0, ',', '.') }} VND</strong></td>
            </tr>
        </tfoot>
    </table>

    @if (isset($editDetail) && $editDetail)
        <div class="adminedit">
            {{-- Form GET để load lại khung giờ khi thay đổi sân hoặc ngày --}}
            <form method="GET" action="{{ route('cap-nhat-chi-tiet-don', $editDetail->order_detail_id) }}" id="form-select-yard-date">
                <label>Chọn sân:</label>
                <select name="yard_id" id="yard_id" required onchange="document.getElementById('form-select-yard-date').submit()">
                    @foreach ($yards as $san)
                        <option value="{{ $san->yard_id }}"
                            {{ request('yard_id', $editDetail->yard_id) == $san->yard_id ? 'selected' : '' }}>
                            {{ $san->name }}
                        </option>
                    @endforeach
                </select><br>

                <label>Ngày thuê:</label>
                <input type="date" name="date" id="date"
                    value="{{ request('date', $editDetail->date) }}"
                    required onchange="document.getElementById('form-select-yard-date').submit()">
            </form>

            {{-- Form POST để cập nhật chi tiết đơn --}}
            <form method="POST" action="{{ route('update.order_detail', $editDetail->order_detail_id) }}">
                @csrf

                <input type="hidden" name="yard_id" value="{{ request('yard_id', $editDetail->yard_id) }}">
                <input type="hidden" name="date" value="{{ request('date', $editDetail->date) }}">

                <label>Khung giờ:</label>
                <select name="time" id="time" required onchange="updatePrice()">
                    @foreach ($timesForSelectedDate as $time)
                        <option value="{{ $time->time }}" data-price="{{ $time->price }}"
                            {{ old('time', $editDetail->time ?? '') == $time->time ? 'selected' : '' }}>
                            {{ $time->time }}
                        </option>
                    @endforeach
                </select><br>

                <label>Giá:</label>
                <input type="text" id="price_display" value="" disabled>
                <input type="hidden" name="price" id="price" value=""><br>

                <label>Ghi chú:</label><br>
                <textarea name="notes" rows="3">{{ old('notes', $editDetail->notes ?? '') }}</textarea><br>

                <button class="update-btn" type="submit">Cập nhật</button><br><br>
            </form>
        </div>
    @endif

    <script>
        // Hàm cập nhật giá khi chọn khung giờ
        function updatePrice() {
            const timeSelect = document.getElementById('time');
            const selectedOption = timeSelect.options[timeSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            document.getElementById('price_display').value = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
            document.getElementById('price').value = price;
        }

        // Khi load trang, hiển thị giá khung giờ đầu tiên
        window.onload = function () {
            updatePrice();
        };
    </script>
@endsection
