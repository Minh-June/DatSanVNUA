@extends('layouts.admin')

@section('title', 'Cáº­p nháº­t thĂ´ng tin Ä‘Æ¡n Ä‘áº·t sĂ¢n')

@section('content')
    @if(session('price_change_message'))
        <script>
            alert("{{ session('price_change_message') }}");
        </script>
    @endif

    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o lá»—i -->
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif
    
    <h3>Chi tiáº¿t Ä‘Æ¡n Ä‘áº·t sĂ¢n</h3>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            <a href="{{ route('quan-ly-don-dat-san') }}">Quay láº¡i danh sĂ¡ch</a>
        </div>
    </div>

    <table id="ListCustomers">
        <thead>
            <tr>
                <th>STT</th>
                <th>TĂªn sĂ¢n</th>
                <th>NgĂ y thuĂª</th>
                <th>Khung giá»</th>
                <th>GiĂ¡</th>
                <th>Ghi chĂº</th>
                <th colspan="2">TĂ¹y chá»n</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderDetails as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->yard->name ?? 'KhĂ´ng xĂ¡c Ä‘á»‹nh' }}</td>
                    <td>{{ \Carbon\Carbon::parse($detail->date)->format('d/m/Y') }}</td>
                    <td>{{ optional($detail->time)->time ?? $detail->time }}</td>
                    <td>{{ number_format($detail->price, 0, ',', '.') }} VND</td>
                    <td>{{ $detail->notes ?: 'KhĂ´ng cĂ³' }}</td>
                    <td>
                        <form action="{{ route('cap-nhat-chi-tiet-don', $detail->order_detail_id) }}" method="GET" style="display:inline;">
                            <button type="submit" class="update-btn">Sá»­a</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('xoa-chi-tiet-don', $detail->order_detail_id) }}" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c muá»‘n xĂ³a chi tiáº¿t nĂ y?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="update-btn">XĂ³a</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right;"><strong>Tá»•ng tiá»n:</strong></td>
                <td colspan="2"><strong>{{ number_format($totalPrice, 0, ',', '.') }} VND</strong></td>
            </tr>
        </tfoot>
    </table>

    @if (isset($editDetail) && $editDetail)
        <div class="adminedit">
            {{-- Form GET Ä‘á»ƒ load láº¡i khung giá» khi thay Ä‘á»•i sĂ¢n hoáº·c ngĂ y --}}
            <form method="GET" action="{{ route('cap-nhat-chi-tiet-don', $editDetail->order_detail_id) }}" id="form-select-yard-date">
                <label>Chá»n sĂ¢n:</label>
                <select name="yard_id" id="yard_id" required onchange="document.getElementById('form-select-yard-date').submit()">
                    @foreach ($yards as $san)
                        <option value="{{ $san->yard_id }}"
                            {{ request('yard_id', $editDetail->yard_id) == $san->yard_id ? 'selected' : '' }}>
                            {{ $san->name }}
                        </option>
                    @endforeach
                </select><br>

                <label>NgĂ y thuĂª:</label>
                <input type="date" name="date" id="date"
                    value="{{ request('date', $editDetail->date) }}"
                    required onchange="document.getElementById('form-select-yard-date').submit()">
            </form>

            {{-- Form POST Ä‘á»ƒ cáº­p nháº­t chi tiáº¿t Ä‘Æ¡n --}}
            <form method="POST" action="{{ route('update.order_detail', $editDetail->order_detail_id) }}">
                @csrf

                <input type="hidden" name="yard_id" value="{{ request('yard_id', $editDetail->yard_id) }}">
                <input type="hidden" name="date" value="{{ request('date', $editDetail->date) }}">

                <label>Khung giá»:</label>
                <select name="time" id="time" required onchange="updatePrice()">
                    @foreach ($timesForSelectedDate as $time)
                        <option value="{{ $time->time }}" data-price="{{ $time->price }}"
                            {{ old('time', $editDetail->time ?? '') == $time->time ? 'selected' : '' }}>
                            {{ $time->time }}
                        </option>
                    @endforeach
                </select><br>

                <label>GiĂ¡:</label>
                <input type="text" id="price_display" value="" disabled>
                <input type="hidden" name="price" id="price" value=""><br>

                <label>Ghi chĂº:</label><br>
                <textarea name="notes" rows="3">{{ old('notes', $editDetail->notes ?? '') }}</textarea><br>

                <button class="update-btn" type="submit">Cáº­p nháº­t</button><br><br>
            </form>
        </div>
    @endif

    <script>
        // HĂ m cáº­p nháº­t giĂ¡ khi chá»n khung giá»
        function updatePrice() {
            const timeSelect = document.getElementById('time');
            const selectedOption = timeSelect.options[timeSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            document.getElementById('price_display').value = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
            document.getElementById('price').value = price;
        }

        // Khi load trang, hiá»ƒn thá»‹ giĂ¡ khung giá» Ä‘áº§u tiĂªn
        window.onload = function () {
            updatePrice();
        };
    </script>
@endsection
