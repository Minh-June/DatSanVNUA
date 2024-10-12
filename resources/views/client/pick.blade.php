@extends('layouts.client.client')

@section('title', 'Đặt sân')

@section('content')
        <!-- Begin: Content -->
        <div id="content" class="order-section">
            <h2 class="order-heading">{{ $tensan }} - {{ $sosan }}</h2>
            <div class="order-content">
                <div class="order">
                    <div class="order-section-left">
                        @if ($image)
                            <img src="{{ asset(Storage::url($image->image)) }}" alt="Sân {{ $sosan }}" class="football-img">
                        @else
                            <img src="{{ asset('./image/football.jpg') }}" alt="Sân {{ $tensan }} - {{ $sosan }}" class="football-img">
                        @endif
                    </div>
                </div>

                <div class="order">
                    <div class="order-section-right">
                        <div class="container">
                            <p>* Lưu ý: Khách hàng muốn đặt sân khác các khung giờ đang có sẵn, vui lòng liên hệ chủ sân theo SĐT: 0356645445 - 0563490783</p>
                            <form action="{{ route('xac-nhan-dat-san') }}" method="post" onsubmit="return validateForm()">
                                @csrf
                                <div class="col col-half form-order-left">
                                    <div class="form-order-left-days">
                                        <label for="date">Chọn ngày:</label>
                                        <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" onchange="fetchBookedTimes()">
                                    </div>
                            
                                    <label for="time">Chọn giờ:</label>
                                    <div class="time-slots">
                                        @foreach ($time_slots as $time_slot)
                                            @php
                                                $is_booked = in_array($time_slot->time_slot, $booked_times); // Kiểm tra nếu giờ đã được đặt
                                            @endphp
                                            <button type="button" class="btn-time {{ $is_booked ? 'booked' : '' }}" 
                                                    onclick="toggleTimeSlot(this)" 
                                                    data-time="{{ $time_slot->time_slot }}" 
                                                    data-price="{{ $time_slot->price }}" 
                                                    {{ $is_booked ? 'disabled' : '' }}>
                                                {{ $time_slot->time_slot }}
                                            </button>
                                        @endforeach
                                    </div>                                    

                                </div>
                            
                                <div class="col col-half form-order-right">
                                    <input type="hidden" name="user_id" value="{{ $userId }}">
                                    <input type="hidden" name="san_id" value="{{ $sanId }}">
                                    <input type="hidden" name="total_price" id="total_price_input" value="0"> 
                                    <input type="hidden" name="selected_times" id="selected_times">
                                    <input type="hidden" name="images[]" value="path_to_image.jpg"> 
                                    <label for="name">Họ và tên:</label>
                                    <input type="text" id="name" name="name" required>
                                    <label for="phone">Số điện thoại:</label>
                                    <input type="text" id="phone" name="phone" required>
                                    <label>Giá tiền: <span id="total_price">0</span></label>
                                    <label for="notes">Ghi chú:</label>
                                    <textarea id="notes" name="notes" rows="4"></textarea>
                                </div>
                                <input type="submit" value="Đặt sân" class="order-button">
                            </form>     
                            
                            <script src="{{ asset('js/datsan.js') }}"></script>
                        </div>
                    </div>
                </div>

            </div>
            <div class="clear"></div>
        </div>
        <!-- End: Content -->
@endsection