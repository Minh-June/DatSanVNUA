@extends('layouts.admin')

@section('title', 'Cập nhật chi tiết đơn đặt sân')

@section('content')
@if(session('price_change_message'))
    <script>alert("{{ session('price_change_message') }}");</script>
@endif

@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

<h2>Chi tiết đơn đặt sân</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-don-dat-san') }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

@php
    $currentUser = auth()->user();

    // Kiểm tra xem có ít nhất 1 chi tiết mà user có quyền chỉnh sửa
    $hasEditPermission = $order->orderDetails->contains(function($detail) use ($currentUser) {
        return $detail->yard && (
            ($currentUser->role == 0 && $detail->yard->user_id == $currentUser->user_id) ||
            ($currentUser->role == 2 && $detail->yard->user_id == $currentUser->user_id)
            // role 3 không được thao tác trên trang này
        );
    });

    // Tính tổng tiền
    $totalPrice = $order->orderDetails->sum('price');
@endphp

<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Ngày thuê</th>
            <th>Loại sân</th>
            <th>Tên sân</th>
            <th>Khung giờ</th>
            <th>Giá (đ)</th>
            <th>Ghi chú</th> {{-- cột Ghi chú mới --}}
            @if($hasEditPermission)
                <th colspan="2">Tùy chọn</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($order->orderDetails as $detail)
            @php
                $canEdit = $detail->yard && (
                    ($currentUser->role == 0 && $detail->yard->user_id == $currentUser->user_id) ||
                    ($currentUser->role == 2 && $detail->yard->user_id == $currentUser->user_id)
                );
            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($detail->date)->format('d/m/Y') }}</td>
                <td>{{ $detail->yard->type->name ?? 'Loại sân không tồn tại' }}</td>
                <td>{{ $detail->yard->name ?? 'Sân không tồn tại' }}</td>
                <td>{{ optional($detail->time)->time ?? $detail->time }}</td>
                <td>{{ number_format($detail->price, 0, ',', '.') }}đ</td>

                {{-- Hiển thị ghi chú --}}
                <td>
                    @if($detail->notes)
                        @foreach(array_chunk(explode(' ', $detail->notes), 8) as $chunk)
                            {{ implode(' ', $chunk) }}<br>
                        @endforeach
                    @else
                        Không có
                    @endif
                </td>

                @if($hasEditPermission)
                    @if($canEdit)
                        <td>
                            <form action="{{ route('cap-nhat-chi-tiet-don', $detail->order_detail_id) }}" method="GET" style="display:inline;">
                                <button type="submit" class="update-btn">Sửa</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('xoa-chi-tiet-don', $detail->order_detail_id) }}" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa chi tiết đơn này không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    @else
                        <td colspan="2"></td>
                    @endif
                @endif
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <td colspan="{{ $hasEditPermission ? 7 : 6 }}" style="text-align: right;"><strong>Tổng tiền:</strong></td>
            <td colspan="{{ $hasEditPermission ? 2 : 1 }}"><strong>{{ number_format($totalPrice, 0, ',', '.') }}đ</strong></td>
        </tr>
    </tfoot>
</table>

{{-- Form cập nhật chi tiết đơn --}}
@if(isset($editDetail) && $editDetail)
<div class="adminedit">
    {{-- Form chọn loại sân, sân, ngày --}}
    <form method="GET" action="{{ route('cap-nhat-chi-tiet-don', $editDetail->order_detail_id) }}" id="form-select-yard-date">
        <div class="adminedit-form-group">
            <label>Loại sân:</label>
            <select name="type_id" id="typeSelect">
                @foreach($types as $type)
                    <option value="{{ $type->type_id }}" {{ $selectedType == $type->type_id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="adminedit-form-group">
            <label>Tên sân:</label>
            <select name="yard_id" id="yardSelect">
                <option value="">Chọn sân</option>
                @foreach($yards as $san)
                    <option value="{{ $san->yard_id }}" {{ $selectedYard == $san->yard_id ? 'selected' : '' }}>
                        {{ $san->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="adminedit-form-group">
            <label>Ngày thuê:</label>
            <input type="date" name="date" value="{{ $selectedDate }}" min="{{ date('Y-m-d') }}" onchange="this.form.submit()">
        </div>
    </form>

    {{-- Form POST: cập nhật chi tiết --}}
    <form method="POST" action="{{ route('update.order_detail', $editDetail->order_detail_id) }}">
        @csrf
        <input type="hidden" name="yard_id" value="{{ $selectedYard }}">
        <input type="hidden" name="date" value="{{ $selectedDate }}">

        <div class="adminedit-form-group">
            <label>Khung giờ:</label>
            <select name="time" id="time" required onchange="updatePrice()" {{ !$selectedYard ? 'disabled' : '' }}>
                <option value="">Chọn khung giờ</option>
                @if($selectedYard && $selectedDate)
                    @foreach($timesForSelectedDate as $time)
                        @if($time['price'] !== null)
                            <option value="{{ $time['time'] }}" data-price="{{ $time['price'] }}"
                                {{ $editDetail->time == $time['time'] ? 'selected' : '' }}>
                                {{ $time['time'] }}
                            </option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>

        <div class="adminedit-form-group">
            <label>Giá tiền:</label>
            <input type="text" id="price_display" disabled value="{{ $selectedYard ? number_format($editDetail->price,0,',','.') .'đ' : '' }}">
            <input type="hidden" name="price" id="price" value="{{ $selectedYard ? $editDetail->price : '' }}">
        </div>

        {{-- Thêm ô Ghi chú --}}
        <div class="adminedit-form-group">
            <label>Ghi chú:</label>
            <input type="text" name="notes" value="{{ old('notes', $editDetail->notes) }}" placeholder="Nhập ghi chú (nếu có)">
        </div>

        <div class="adminedit-button">
            <button type="submit" class="update-btn" {{ !$selectedYard ? 'disabled' : '' }}>Cập nhật thông tin</button>
        </div>
    </form>
</div>

{{-- jQuery AJAX load sân theo loại --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#typeSelect').on('change', function() {
        let typeId = $(this).val();
        $('#yardSelect').html('<option value="">Chọn sân</option>');

        if(typeId) {
            $.ajax({
                url: '/admin/yards-by-type/' + typeId,
                type: 'GET',
                success: function(data) {
                    data.forEach(function(yard) {
                        $('#yardSelect').append('<option value="'+yard.yard_id+'">'+yard.name+'</option>');
                    });
                },
                error: function() { alert('Không thể load sân!'); }
            });
        }
    });

    window.updatePrice = function() {
        const timeSelect = document.getElementById('time');
        const selectedOption = timeSelect?.options[timeSelect.selectedIndex];
        const price = selectedOption?.getAttribute('data-price') || '';
        document.getElementById('price_display').value = price ? parseInt(price).toLocaleString('vi-VN') + 'đ' : '';
        document.getElementById('price').value = price || '';
    };

    if("{{ $selectedYard }}") updatePrice();
});
</script>
@endif

@endsection
