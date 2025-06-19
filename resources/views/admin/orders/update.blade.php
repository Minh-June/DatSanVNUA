@extends('layouts.admin')

@section('title', 'Cập nhật chi tiết đơn đặt sân')

@section('content')
    @if(session('price_change_message'))
        <script>
            alert("{{ session('price_change_message') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif
    
    <h2>Chi tiết đơn đặt sân</h2>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            <a class="update-btn" href="{{ route('quan-ly-don-dat-san') }}">Quay lại danh sách</a>
        </div>
    </div>

    <table id="ListCustomers">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ngày thuê</th>
                <th>Loại sân</th>
                <th>Tên sân</th>
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
                    <td>{{ \Carbon\Carbon::parse($detail->date)->format('d/m/Y') }}</td>
                    <td class="left-align">{{ $detail->yard->type->name ?? 'Loại sân không tồn tại' }}</td>
                    <td class="left-align">{{ $detail->yard->name ?? 'Sân không tồn tại' }}</td>
                    <td>{{ optional($detail->time)->time ?? $detail->time }}</td>
                    <td>{{ number_format($detail->price, 0, ',', '.') }}đ</td>
                    <td>{{ $detail->notes ?: 'Không có' }}</td>
                    <td>
                        <form action="{{ route('cap-nhat-chi-tiet-don', $detail->order_detail_id) }}" method="GET" style="display:inline;">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('xoa-chi-tiet-don', $detail->order_detail_id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chi tiết đơn này không?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right;"><strong>Tổng tiền:</strong></td>
                <td colspan="2"><strong>{{ number_format($totalPrice, 0, ',', '.') }}đ</strong></td>
            </tr>
        </tfoot>
    </table>

    @if (isset($editDetail) && $editDetail && !session('price_change_message'))
        <div class="adminedit">
            <form method="GET" action="{{ route('cap-nhat-chi-tiet-don', $editDetail->order_detail_id) }}" id="form-select-yard-date">
                {{-- Chọn loại sân --}}
                <div class="adminedit-form-group">
                    <label>Loại sân:</label>
                    <select name="type_id" id="type_id" onchange="document.getElementById('form-select-yard-date').submit()">
                        @foreach ($types as $type)
                            <option value="{{ $type->type_id }}" 
                                {{ request('type_id', $editDetail->yard->type_id ?? '') == $type->type_id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Chọn tên sân (lọc theo loại sân nếu có) --}}
                <div class="adminedit-form-group">
                    <label>Tên sân:</label>
                    <select name="yard_id" id="yard_id" onchange="document.getElementById('form-select-yard-date').submit()" required>
                        @foreach ($yards->where('type_id', request('type_id', $editDetail->yard->type_id)) as $san)
                            <option value="{{ $san->yard_id }}" {{ request('yard_id', $editDetail->yard_id) == $san->yard_id ? 'selected' : '' }}>
                                {{ $san->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Ngày thuê --}}
                <div class="adminedit-form-group">
                    <label>Ngày thuê:</label>
                    <input type="date" name="date" id="date"
                        value="{{ request('date', $editDetail->date) }}"
                        required onchange="document.getElementById('form-select-yard-date').submit()"
                        min="{{ date('Y-m-d') }}">
                </div>
            </form>

            {{-- Form POST để cập nhật chi tiết đơn --}}
            <form method="POST" action="{{ route('update.order_detail', $editDetail->order_detail_id) }}">
                @csrf

                <input type="hidden" name="yard_id" value="{{ request('yard_id', $editDetail->yard_id) }}">
                <input type="hidden" name="date" value="{{ request('date', $editDetail->date) }}">
                <div class="adminedit-form-group">
                    <label>Khung giờ:</label>
                    <select name="time" id="time" required onchange="updatePrice()">
                        @foreach ($timesForSelectedDate as $time)
                            <option value="{{ $time->time }}" data-price="{{ $time->price }}"
                                {{ old('time', $editDetail->time ?? '') == $time->time ? 'selected' : '' }}>
                                {{ $time->time }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="adminedit-form-group">
                    <label>Giá tiền:</label>
                    <input type="text" id="price_display" value="" disabled>
                    <input type="hidden" name="price" id="price" value="">
                </div>

                <div class="adminedit-form-group">
                    <label>Ghi chú:</label><br>
                    <textarea name="notes" rows="3">{{ old('notes', $editDetail->notes ?? '') }}</textarea>
                </div>

                <div class="adminedit-button">
                    <button class="update-btn" type="submit">Cập nhật</button>
                </div>
            </form>
        </div>
    @endif

    <script>
        // Hàm cập nhật giá khi chọn khung giờ
        function updatePrice() {
            const timeSelect = document.getElementById('time');
            const selectedOption = timeSelect.options[timeSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            document.getElementById('price_display').value = parseInt(price).toLocaleString('vi-VN') + 'đ';
            document.getElementById('price').value = price;
        }

        // Khi load trang, hiển thị giá khung giờ đầu tiên
        window.onload = function () {
            updatePrice();
        };
    </script>
@endsection
