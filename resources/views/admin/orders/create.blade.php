@extends('layouts.admin')

@section('title', 'Thêm đơn đặt sân')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <h3>Thêm đơn đặt sân</h3>

    <div class="adminedit">
        {{-- Form GET để chọn sân và ngày và load lại trang để cập nhật khung giờ --}}
        <form method="GET" action="{{ route('them-don-dat-san') }}" id="form-select-yard-date">

            <label>Chọn sân:</label>
            <select name="yard_id" required onchange="document.getElementById('form-select-yard-date').submit()">
                @foreach ($yards as $yard)
                    <option value="{{ $yard->yard_id }}" {{ request('yard_id') == $yard->yard_id ? 'selected' : '' }}>
                        {{ $yard->name }}
                    </option>
                @endforeach
            </select><br>

            <label>Ngày thuê:</label>
            <input type="date" name="date" value="{{ request('date') ?? old('date') }}" required onchange="document.getElementById('form-select-yard-date').submit()">
        </form>

        {{-- Form POST để lưu đơn đặt sân --}}
        <form method="POST" action="{{ route('luu-don-dat-san') }}">
            @csrf
            <input type="hidden" name="yard_id" value="{{ request('yard_id') }}">
            <input type="hidden" name="date" value="{{ request('date') }}">

            <label>Khung giờ:</label>
            <select name="time" id="time" required onchange="updatePrice()">
                @foreach ($timesForSelectedDate as $time)
                    <option value="{{ $time->time }}" data-price="{{ $time->price }}">
                        {{ $time->time }}
                    </option>
                @endforeach
            </select><br>

            <label>Thành tiền:</label>
            <input type="text" id="price_display" disabled>
            <input type="hidden" name="price" id="price"><br>

            <label>Họ và tên:</label>
            <input type="text" name="name" value="{{ old('name') }}" required><br>

            <label>Số điện thoại:</label>
            <input type="text" name="phone" value="{{ old('phone') }}" required><br>

            <label>Ghi chú:</label><br><br>
            <textarea name="notes" rows="3">{{ old('notes') }}</textarea><br>

            <button type="submit" class="update-btn">Xác nhận thêm đơn</button><br><br>
        </form>
    </div>

    <script>
        function updatePrice() {
            const timeSelect = document.getElementById('time');
            const selectedOption = timeSelect.options[timeSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            document.getElementById('price_display').value = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
            document.getElementById('price').value = price;
        }
        window.onload = function() {
            updatePrice();
        };
    </script>
@endsection
