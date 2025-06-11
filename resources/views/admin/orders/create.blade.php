@extends('layouts.admin')

@section('title', 'ThĂªm Ä‘Æ¡n Ä‘áº·t sĂ¢n')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <h3>ThĂªm Ä‘Æ¡n Ä‘áº·t sĂ¢n</h3>

    <div class="adminedit">
        {{-- Form GET Ä‘á»ƒ chá»n sĂ¢n vĂ  ngĂ y vĂ  load láº¡i trang Ä‘á»ƒ cáº­p nháº­t khung giá» --}}
        <form method="GET" action="{{ route('them-don-dat-san') }}" id="form-select-yard-date">

            <label>Chá»n sĂ¢n:</label>
            <select name="yard_id" required onchange="document.getElementById('form-select-yard-date').submit()">
                @foreach ($yards as $yard)
                    <option value="{{ $yard->yard_id }}" {{ request('yard_id') == $yard->yard_id ? 'selected' : '' }}>
                        {{ $yard->name }}
                    </option>
                @endforeach
            </select><br>

            <label>NgĂ y thuĂª:</label>
            <input type="date" name="date" value="{{ request('date') ?? old('date') }}" required onchange="document.getElementById('form-select-yard-date').submit()">
        </form>

        {{-- Form POST Ä‘á»ƒ lÆ°u Ä‘Æ¡n Ä‘áº·t sĂ¢n --}}
        <form method="POST" action="{{ route('luu-don-dat-san') }}">
            @csrf
            <input type="hidden" name="yard_id" value="{{ request('yard_id') }}">
            <input type="hidden" name="date" value="{{ request('date') }}">

            <label>Khung giá»:</label>
            <select name="time" id="time" required onchange="updatePrice()">
                @foreach ($timesForSelectedDate as $time)
                    <option value="{{ $time->time }}" data-price="{{ $time->price }}">
                        {{ $time->time }}
                    </option>
                @endforeach
            </select><br>

            <label>ThĂ nh tiá»n:</label>
            <input type="text" id="price_display" disabled>
            <input type="hidden" name="price" id="price"><br>

            <label>Há» vĂ  tĂªn:</label>
            <input type="text" name="name" value="{{ old('name') }}" required pattern="^[\p{L}\s]+$" title="Chá»‰ nháº­p chá»¯ cĂ¡i vĂ  khoáº£ng tráº¯ng"><br>

            <label>Sá»‘ Ä‘iá»‡n thoáº¡i:</label>
            <input type="text" name="phone" value="{{ old('phone') }}" required pattern="^[0-9]+$" title="Chá»‰ nháº­p sá»‘"><br>

            <label>Ghi chĂº:</label><br><br>
            <textarea name="notes" rows="3">{{ old('notes') }}</textarea><br>

            <button type="submit" class="update-btn">XĂ¡c nháº­n thĂªm Ä‘Æ¡n</button><br><br>
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
