@extends('layouts.client.client')

@section('title', 'Äáº·t sĂ¢n')

@section('content')

@if (session('success'))
    <script>
        alert('Äáº·t sĂ¢n thĂ nh cĂ´ng!');
    </script>
@endif

<div id="content" class="order-section">
    <h2 class="order-heading">{{ $yard_name }}</h2>
    <div class="order-content">
        <div class="order">
            <div class="order-section-left">
                @if ($yard_image)
                    <img src="{{ asset(Storage::url($yard_image->image)) }}" alt="SĂ¢n {{ $yard_name }}" class="football-img" style="cursor: pointer;" onclick="showAllImages()">
                @else
                    <img src="{{ asset('image/football.jpg') }}" alt="SĂ¢n {{ $yard_name }}" class="football-img">
                @endif
            </div>
        </div>

        <div class="order">
            <div class="order-section-right">
                <div class="container">
                    <p>* LÆ°u Ă½: Náº¿u báº¡n muá»‘n Ä‘áº·t sĂ¢n ngoĂ i khung giá» cĂ³ sáºµn, vui lĂ²ng liĂªn há»‡ chá»§ sĂ¢n qua SÄT: 0356645445</p>
                    <form action="{{ route('luu-thong-tin-don-dat-san') }}" method="POST" id="orderForm" onsubmit="return confirmBooking(event)">
                        @csrf
                        <div class="form-order-left-days">
                            <label for="date">Chá»n ngĂ y:</label>
                            <input type="hidden" id="yard_id_input" value="{{ $yard_id }}">
                            <input type="date" id="date" name="date"
                                value="{{ old('date', $selected_date ?? date('Y-m-d')) }}"
                                min="{{ date('Y-m-d') }}"
                                onchange="onDateChange()">
                        </div>

                        <label for="time">Chá»n giá»:</label>
                        <div class="time-slots" id="time_slots_container">
                            @foreach ($times as $slot)
                                @php
                                    // Disable náº¿u Ä‘Ă£ Ä‘Æ°á»£c admin xĂ¡c nháº­n
                                    $isAdminBooked = in_array($slot->time, $adminBookedTimes);

                                    // Disable náº¿u khung giá» cĂ³ trong session user hiá»‡n táº¡i
                                    $isSessionBooked = in_array($slot->time, $sessionBookedTimes);

                                    // Tá»•ng há»£p tráº¡ng thĂ¡i disable
                                    $disabled = $isAdminBooked || $isSessionBooked;
                                @endphp
                                <button type="button" class="btn-time {{ $disabled ? 'booked' : '' }}"
                                        data-time="{{ $slot->time }}"
                                        data-price="{{ $slot->price }}"
                                        {{ $disabled ? 'disabled' : '' }}>
                                    {{ $slot->time }}
                                </button>
                            @endforeach
                        </div>

                        <div class="form-order-right">
                            <input type="hidden" name="user_id" value="{{ $userId  }}">
                            <input type="hidden" name="yard_id" value="{{ $yard_id }}">
                            <input type="hidden" name="total_price" id="total_price_input" value="0">
                            <div id="selected_times"></div>
                            <input type="hidden" name="continue_booking" id="continue_booking">
                            <input type="hidden" name="name" value="{{ $user->fullname ?? '' }}">
                            <input type="hidden" name="phone" value="{{ $user->phonenb ?? '' }}">
                            <label>ThĂ nh tiá»n: <span id="total_price">0 VND</span></label>
                            <label for="notes">Ghi chĂº:</label>
                            <textarea id="notes" name="notes" rows="4">{{ old('notes') }}</textarea>
                            <button type="submit" class="order-button">Äáº·t sĂ¢n</button>
                        </div>
                    </form>

                    <script src="{{ asset('js/datsan.js') }}"></script>
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>

    <!-- Lightbox hiá»‡n táº¥t cáº£ áº£nh sĂ¢n -->
    <div id="multi-image-popup" onclick="hideAllImages()" style="
        display: none;
        position: fixed;
        z-index: 2;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.8);
        justify-content: center;
        align-items: center;
    ">
        <div onclick="event.stopPropagation()" style="display: flex; gap: 15px;">
            @foreach ($yard->images as $img)
                <img src="{{ asset(Storage::url($img->image)) }}"
                    alt="áº¢nh sĂ¢n"
                    style="max-height: 700px; max-width: 525px; box-shadow: 0 0 10px #000;">
            @endforeach
        </div>
    </div>

    <script>
        function showAllImages() {
            document.getElementById('multi-image-popup').style.display = 'flex';
        }

        function hideAllImages() {
            document.getElementById('multi-image-popup').style.display = 'none';
        }
    </script>
@endsection
