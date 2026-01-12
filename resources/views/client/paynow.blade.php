@extends('layouts.client.client')

@section('title', 'Thanh toán ngay')

@section('content')
@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif  

@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

<div id="content" class="order-section">
    <h2 class="order-heading">THANH TOÁN</h2>
    
    {{-- Thời gian đếm ngược --}}
    <div class="pay-method" style="margin-bottom: 20px;">
        <p style="color:red;">Đơn của bạn còn được giữ chỗ trong</p>
        <p id="countdown" style="color:red;">10:00</p>
    </div>

    {{-- Chọn hình thức thanh toán --}}
    <div class="pay-method" style="margin-bottom: 20px;">
        <p>Hình thức thanh toán</p>
        <label style="display: inline-flex; align-items: center; margin-right: 20px; cursor: pointer;">
            <input type="radio" name="payment_method" value="offline" checked style="margin-right: 5px;">
            <a href="{{ url('/thanh-toan') }}" style="color: #333; text-decoration: none; font-size:20px; margin-top:-5px;">Thanh toán trực tiếp tại sân</a>
        </label>

        <label>
            <input type="radio" name="payment_method" value="online" checked>
            Thanh toán trước khi đến sân
        </label>
    </div>

    @php
        // Tạo orderKey riêng cho đơn hiện tại
        $orderKey = session('current_order_key') ?? uniqid('order_');
        session(['current_order_key' => $orderKey]);
    @endphp

    {{-- Form thanh toán online --}}
    <div class="pay-customer online-group">
        @php
            $ownerModel = \App\Models\User::find($ownerOrders[0]['yard_owner_id'] ?? 0);
        @endphp
        <p>Thông tin thanh toán</p>
        <div class="pay-content" style="flex-wrap: wrap; margin-bottom: 20px;">
            <div class="pay-information">
                <div class="bank-account">Thông tin ngân hàng</div>
                @if($ownerModel)
                    <div class="bank-account">Ngân hàng: {{ $ownerModel->acc_type ?? '' }}</div>
                    <div class="bank-account">Số tài khoản: {{ $ownerModel->acc_number ?? '' }}</div>
                    <div class="bank-account">Tên tài khoản: {{ $ownerModel->acc_name ?? '' }}</div>
                @endif
            </div>
            <div class="pay-information">
                @if(!empty($ownerModel?->qr_code))
                    <div class="bank-qr">
                        <img class="bank-qr-img" src="{{ asset('storage/' . $ownerModel->qr_code) }}" alt="Mã QR"><br>
                        Mã QR
                    </div>
                @endif
            </div>
        </div>

        <p>Thông tin đơn đặt sân</p><br>
        <table id="ListCustomers">
            <thead>
                <tr>
                    <th>Họ và tên</th>
                    <th>SĐT</th>
                    <th>Ngày thuê</th>
                    <th>Loại sân</th>
                    <th>Tên sân</th>
                    <th>Thời gian</th>
                    <th>Giá (đ)</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $ordersCollection = collect($ownerOrders);
                    $groupedByUser = $ordersCollection->groupBy(fn($o) => $o['name'].'-'.$o['phone']);
                    $totalAmount = 0;
                @endphp

                @foreach($groupedByUser as $userGroup)
                    @php
                        $rowspanNamePhone = $userGroup->count();
                        $firstNamePhoneRow = true;
                        $groupedByDate = $userGroup->groupBy('date');
                    @endphp

                    @foreach($groupedByDate as $date => $dateGroup)
                        @php
                            $rowspanDate = $dateGroup->count();
                            $firstDateRow = true;
                            $groupedByType = $dateGroup->groupBy('type_name');
                        @endphp

                        @foreach($groupedByType as $type => $typeGroup)
                            @php
                                $rowspanType = $typeGroup->count();
                                $firstTypeRow = true;
                                $groupedByYard = $typeGroup->groupBy('yard_name');
                            @endphp

                            @foreach($groupedByYard as $yard => $yardGroup)
                                @php
                                    $rowspanYard = $yardGroup->count();
                                    $firstYardRow = true;
                                @endphp

                                @foreach($yardGroup as $order)
                                    <tr>
                                        {{-- Họ và tên --}}
                                        @if($firstNamePhoneRow)
                                            <td rowspan="{{ $rowspanNamePhone }}">{{ $order['name'] }}</td>
                                            <td rowspan="{{ $rowspanNamePhone }}">{{ $order['phone'] }}</td>
                                            @php $firstNamePhoneRow = false; @endphp
                                        @endif

                                        {{-- Ngày đặt --}}
                                        @if($firstDateRow)
                                            <td rowspan="{{ $rowspanDate }}">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                            @php $firstDateRow = false; @endphp
                                        @endif

                                        {{-- Loại sân --}}
                                        @if($firstTypeRow)
                                            <td rowspan="{{ $rowspanType }}">{{ $type }}</td>
                                            @php $firstTypeRow = false; @endphp
                                        @endif

                                        {{-- Tên sân --}}
                                        @if($firstYardRow)
                                            <td rowspan="{{ $rowspanYard }}">{{ $yard }}</td>
                                            @php $firstYardRow = false; @endphp
                                        @endif

                                        {{-- Thời gian thuê --}}
                                        <td>
                                            @foreach($order['times'] as $time)
                                                {{ $time }}<br>
                                            @endforeach
                                        </td>

                                        {{-- Giá từng khung giờ --}}
                                        <td>
                                            @foreach($order['price_per_slot'] ?? [] as $price)
                                                {{ number_format($price) }}đ<br>
                                                @php $totalAmount += $price; @endphp
                                            @endforeach
                                        </td>

                                        {{-- Ghi chú --}}
                                        <td>
                                            @php
                                                // Tách chuỗi thành mảng từ
                                                $words = isset($order['notes']) ? explode(' ', $order['notes']) : ['Không', 'có'];
                                                // Chia mảng thành từng chunk 5 từ
                                                $chunks = array_chunk($words, 7);
                                            @endphp

                                            @foreach($chunks as $chunk)
                                                {{ implode(' ', $chunk) }}<br>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach

                {{-- Hàng tổng tiền --}}
                @if($totalAmount > 0)
                    <tr>
                        <td colspan="6" style="text-align:right;font-weight:bold;">Tổng tiền:</td>
                        <td colspan="2" style="font-weight:bold;">{{ number_format($totalAmount) }}đ</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <form action="{{ route('pay.online') }}" method="post" enctype="multipart/form-data">
            @csrf

            {{-- Hidden input để gửi notes lên server --}}
            <input type="hidden" name="notes" 
                value="{{ isset($ownerOrders[0]['notes']) ? $ownerOrders[0]['notes'] : 'Không có' }}">

            <input type="hidden" name="owner_id" value="{{ $ownerModel->user_id ?? 0 }}">

            <div class="pay-upload">
                <p>* LƯU Ý:<br>
                    Nội dung chuyển khoản: TÊN + SĐT<br>
                    Chuyển khoản ĐÚNG số tiền ở phần "Tổng tiền"<br>
                    Sau khi hoàn tất, chụp lại màn hình giao dịch và gửi ảnh bên dưới
                </p><br>
                <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png"><br><br>
            </div>

            <div class="pay-btn" style="margin-bottom: 100px; text-align: center;">
                <button type="submit" class="order-football-btn">Xác nhận đặt sân</button>
            </div>
        </form>
        
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="{{ route('pay.online') }}"]');
            form.addEventListener('submit', function(e) {
                const files = form.querySelector('input[name="images[]"]').files;
                if (files.length === 0) {
                    e.preventDefault();
                    alert("Vui lòng tải ảnh thanh toán thành công hoặc chọn phương thức thanh toán khác.");
                }
            });
        });
        </script>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const countdownEl = document.getElementById('countdown');

    // orderKey giống với OFFline để countdown tiếp nối
    const orderKey = "{{ $orderKey }}";

    // Lấy thời gian còn lại từ localStorage
    let remainingTime = localStorage.getItem('payment_remaining_' + orderKey);
    if (!remainingTime) {
        remainingTime = 10 * 60; // <-- chỉnh ở đây nếu muốn thay đổi mặc định
    }
    remainingTime = parseInt(remainingTime);

    const timer = setInterval(() => {
        if (remainingTime <= 0) {
            clearInterval(timer);
            localStorage.removeItem('payment_remaining_' + orderKey);

            // Hiện alert, người dùng chỉ có nút OK
            alert("Vui lòng quay về trang chủ đặt sân !");
            window.location.href = "{{ route('payment.timeout') }}";
            return;
        }

        const min = Math.floor(remainingTime / 60);
        const sec = remainingTime % 60;
        countdownEl.textContent = `${min.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')}`;

        remainingTime--;
        localStorage.setItem('payment_remaining_' + orderKey, remainingTime);
    }, 1000);

    // --- Hiển thị form theo radio ---
    const offlineGroup = document.querySelector('.offline-group');
    const onlineGroup = document.querySelector('.online-group');
    const offlineRadio = document.querySelector('input[name="payment_method"][value="offline"]');
    const onlineRadio = document.querySelector('input[name="payment_method"][value="online"]');

    function updateLayout(value) {
        if (value === 'online') {
            offlineGroup && (offlineGroup.style.display = 'none');
            onlineGroup && (onlineGroup.style.display = 'block');
        } else {
            offlineGroup && (offlineGroup.style.display = 'block');
            onlineGroup && (onlineGroup.style.display = 'none');
        }
    }

    // Khởi tạo layout
    const selected = localStorage.getItem('payment_method_' + orderKey) || 
                     document.querySelector('input[name="payment_method"]:checked')?.value || 'offline';
    updateLayout(selected);
    [offlineRadio, onlineRadio].forEach(r => r.checked = (r.value === selected));

    // Lắng nghe thay đổi radio
    [offlineRadio, onlineRadio].forEach(radio => {
        radio.addEventListener('change', function () {
            updateLayout(this.value);
            localStorage.setItem('payment_method_' + orderKey, this.value);
        });
    });

    // Dừng countdown khi submit form Online
    const onlineForm = document.querySelector('.online-group form');
    if (onlineForm) {
        onlineForm.addEventListener('submit', function () {
            clearInterval(timer);
            localStorage.removeItem('payment_remaining_' + orderKey); // reset countdown cho lần đặt tiếp theo
        });
    }
});
</script>

@endsection
