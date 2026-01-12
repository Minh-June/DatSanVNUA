@extends('layouts.admin')

@section('title','Quản lý chi tiết đơn thuê cố định theo tháng')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif
@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

<h2>Chi tiết đơn cố định</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-don-dat-san-co-dinh') }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Loại sân</th>
            <th>Sân</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Thứ</th>
            <th>Khung giờ</th>
            <th>Giá (đ)</th>
            @if(auth()->user()->role != 3)
                <th>Tùy chọn</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @php $stt = 1; @endphp
        <tr>
            <td>{{ $stt++ }}</td>
            <td>{{ $order->yard->type->name ?? '' }}</td>
            <td>{{ $order->yard->name ?? '' }}</td>
            <td>{{ \Carbon\Carbon::parse($order->from_date)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($order->to_date)->format('d/m/Y') }}</td>
            <td>
                @foreach(explode(',', $order->weekday) as $day)
                    {{ ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'][$day] ?? '' }}<br>
                @endforeach
            </td>
            <td>{{ \Carbon\Carbon::parse($order->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($order->end)->format('H:i') }}</td>
            <td>{{ number_format($order->price, 0, ',', '.') }}đ</td>
            @if(auth()->user()->role != 3)
            <td>
                <form action="{{ route('cap-nhat-chi-tiet-don-dat-san-co-dinh', $order->month_rent_id) }}" method="GET">
                    <input type="hidden" name="edit" value="1">
                    <button type="submit" class="update-btn">Sửa</button>
                </form>
            </td>
            @endif
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" style="text-align: right;"><strong>Tổng tiền:</strong></td>
            <td colspan="2"><strong>{{ number_format($order->price, 0, ',', '.') }}đ</strong></td>
        </tr>
    </tfoot>
</table>

@if(isset($editDetail) && $editDetail)
<h2>Cập nhật thông tin chi tiết</h2>

<div class="month-rent-content">

    {{-- Hiển thị lỗi validation --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('update.fixedorder_detail', ['order_id' => $editDetail->month_rent_id]) }}">
        @csrf

        {{-- Tên sân --}}
        <div class="form-group">
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

        {{-- Ngày bắt đầu --}}
        <div class="form-group">
            <label>Ngày bắt đầu:</label>
            <input type="date" name="from_date" value="{{ old('from_date', $from_date) }}" min="{{ date('Y-m-d') }}">
        </div>

        {{-- Ngày kết thúc --}}
        <div class="form-group">
            <label>Ngày kết thúc:</label>
            <input type="date" name="to_date" value="{{ old('to_date', $to_date) }}" min="{{ date('Y-m-d') }}">
        </div>

        {{-- Ngày trong tuần --}}
        <div class="form-group">
            <label>Ngày trong tuần:</label>
            <div class="weekday-list">
                @php $days = ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật']; @endphp
                @foreach($days as $index => $day)
                    <span class="weekday-item {{ in_array($index, $selectedWeekdays) ? 'selected' : '' }}"
                          data-value="{{ $index }}" onclick="toggleWeekday(this)">
                        {{ $day }}
                    </span>
                @endforeach
            </div>
            <input type="hidden" name="weekdays" id="selectedWeekdays" value="{{ implode(',', $selectedWeekdays) }}">
        </div>

        {{-- Giờ thuê --}}
        <div class="form-group">
            <label>Giờ bắt đầu:</label>
            <input type="time" name="start" value="{{ \Carbon\Carbon::parse($time_from)->format('H:i') }}" min="06:00" max="22:30">
        </div>
        <div class="form-group">
            <label>Giờ kết thúc:</label>
            <input type="time" name="end" value="{{ \Carbon\Carbon::parse($time_to)->format('H:i') }}" min="06:30" max="22:30">
        </div>

        {{-- Giá tiền --}}
        <div class="form-group">
            <label>Giá tiền (đ):</label>
            <input type="text" id="price_display" value="{{ number_format($price,0,',','.') }}">
            <input type="hidden" name="price" id="price" value="{{ $price }}">
        </div>

        <button type="submit" style="font-size: 18px; margin-bottom:30px;" class="monthly-submit-btn">Cập nhật thông tin</button>
    </form>
</div>

<style>
    .month-rent-content {
        width: 54%;
        margin: 30px auto;
    }

    .month-rent-content .weekday-item {
        padding: 8px 12px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // Toggle chọn ngày trong tuần
    function updateWeekdaysInput() {
        let selected = [];
        $('.weekday-item.selected').each(function() {
            selected.push($(this).data('value'));
        });
        $('#selectedWeekdays').val(selected.join(','));
    }

    window.toggleWeekday = function(el) {
        $(el).toggleClass('selected');
        updateWeekdaysInput();
    };

    // Đồng bộ giá tiền nhập tay với hidden input
    $('#price_display').on('input', function(){
        let val = $(this).val().replace(/\D/g,'');
        $('#price').val(val);
    });

    // Cập nhật hidden weekdays khi load
    updateWeekdaysInput();
});
</script>
@endif
@endsection
