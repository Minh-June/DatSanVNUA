@extends('layouts.client.client')

@section('title', 'Đặt sân')

@section('content')

@if (session('success'))
    <script>
        alert('Đặt sân thành công!');
    </script>
@endif

<div id="content" class="order-section">
    <h2 class="order-heading">{{ $type_name }} - {{ $yard_name }}</h2>
    <div class="order-content">
        <div class="order-section-left">
            @if ($yard_image)
                <img src="{{ asset(Storage::url($yard_image->image)) }}" alt="Sân {{ $yard_name }}" class="football-img" style="cursor: pointer;" onclick="showAllImages()">
            @else
                <img src="{{ asset('image/football.jpg') }}" alt="Sân {{ $yard_name }}" class="football-img">
            @endif
        </div>

        <div class="order-section-right">
            <div class="container">
                <p>* Lưu ý: Nếu bạn muốn đặt sân ngoài khung giờ có sẵn, vui lòng liên hệ chủ sân qua SĐT: 0356645445</p>
                <form action="{{ route('luu-thong-tin-don-dat-san') }}" method="POST" id="orderForm" onsubmit="return confirmBooking(event)">
                    @csrf
                    <input type="hidden" name="type_name" value="{{ $type_name }}">
                    <div class="form-order-days">
                        <label for="date">Chọn ngày:</label>
                        <input type="hidden" id="yard_id_input" value="{{ $yard_id }}">
                        <input type="date" id="date" name="date"
                            value="{{ old('date', $selected_date ?? date('Y-m-d')) }}"
                            min="{{ date('Y-m-d') }}"
                            onchange="onDateChange()">
                    </div>

                    <div class="form-order-times">
                        <label for="time">Chọn giờ:</label>
                        <div class="time-slots" id="time_slots_container">
                            @foreach ($times as $slot)
                                @php
                                    $isAdminBooked = in_array($slot->time, $adminBookedTimes);
                                    $isSessionBooked = in_array($slot->time, $sessionBookedTimes);
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
                    </div>

                    <input type="hidden" name="user_id" value="{{ $userId  }}">
                    <input type="hidden" name="yard_id" value="{{ $yard_id }}">
                    <input type="hidden" name="type_id" value="{{ $type_id }}">
                    <input type="hidden" name="type_name" value="{{ $type_name }}">
                    <input type="hidden" name="total_price" id="total_price_input" value="0">
                    <div id="selected_times"></div>
                    <input type="hidden" name="continue_booking" id="continue_booking">
                    <input type="hidden" name="name" value="{{ $user->fullname ?? '' }}">
                    <input type="hidden" name="phone" value="{{ $user->phonenb ?? '' }}">

                    <label>Thành tiền: <span id="total_price">0 VNĐ</span></label><br><br>
                    <label for="notes">Ghi chú:</label>
                    <textarea id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                    <button type="submit" class="order-football-btn">Đặt sân</button>
                </form>

                <script src="{{ asset('js/datsan.js') }}"></script>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>

    <!-- Hiển thị tất cả ảnh sân -->
    <div id="multi-image-popup" onclick="hideAllImages()" style="
        display: none;
        position: fixed;
        z-index: 2;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.8);
        justify-content: center;
        align-items: center;
    ">
        <div onclick="event.stopPropagation()" style="
            display: flex; 
            gap: 15px;
            max-width: 90%;
            overflow-x: auto;
        ">
            @foreach ($yard->images as $img)
                <img src="{{ asset(Storage::url($img->image)) }}"
                    alt="Ảnh sân"
                    style="max-height: 600px; max-width: 500px; box-shadow: 0 0 10px #000;">
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
