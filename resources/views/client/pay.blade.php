@extends('layouts.client.client')

@section('title', 'Thanh toán')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif  
    
    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

<div id="content" class="order-section">
    <h2 class="order-heading">THANH TOÁN</h2>

    <div class="pay-content">
        <div class="pay-information">
            <div class="bank-account">Tài khoản ngân hàng</div>
            <div class="bank-account">Tên tài khoản: Nguyễn Hữu Quang Minh</div>
            <div class="bank-account">Số tài khoản: 1903 6786 8800 12</div>
            <div class="bank-account">Ngân hàng: Techcombank</div>
        </div>
        <div class="pay-information">
            <div class="bank-qr">
                <img class="bank-qr-img" src="{{ asset('image/qr/qr.jpg') }}" alt="Mã QR"> <br>
                Mã QR
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <div class="pay-customer">
        <h2>Thông tin đơn đặt sân</h2><br>

        <table id="ListCustomers">
            <thead>
                <tr>
                    <th>Họ và tên</th>
                    <th>SĐT</th>
                    <th>Ngày đặt</th>
                    <th>Loại sân</th>
                    <th>Tên sân</th>
                    <th>Thời gian thuê</th>
                    <th>Giá từng khung giờ</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalAmount = 0;
                    $groupedByUser = collect($orders)->groupBy(fn($o) => $o['name'] . '-' . $o['phone']);
                @endphp

                @forelse ($groupedByUser as $userGroup)
                    @php
                        $rowspanNamePhone = $userGroup->count();
                        $firstNamePhoneRow = true;
                        $groupedByDate = $userGroup->groupBy('date');
                    @endphp

                    @foreach ($groupedByDate as $date => $dateGroup)
                        @php
                            $rowspanDate = $dateGroup->count();
                            $firstDateRow = true;
                            $groupedByType = $dateGroup->groupBy('type_name');
                        @endphp

                        @foreach ($groupedByType as $type => $typeGroup)
                            @php
                                $rowspanType = $typeGroup->count();
                                $firstTypeRow = true;
                            @endphp

                            @foreach ($typeGroup as $order)
                                <tr>
                                    {{-- Gộp họ tên + SĐT --}}
                                    @if ($firstNamePhoneRow)
                                        <td rowspan="{{ $rowspanNamePhone }}">{{ $order['name'] }}</td>
                                        <td rowspan="{{ $rowspanNamePhone }}">{{ $order['phone'] }}</td>
                                        @php $firstNamePhoneRow = false; @endphp
                                    @endif

                                    {{-- Gộp ngày đặt --}}
                                    @if ($firstDateRow)
                                        <td rowspan="{{ $rowspanDate }}">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</td>
                                        @php $firstDateRow = false; @endphp
                                    @endif

                                    {{-- Gộp loại sân --}}
                                    @if ($firstTypeRow)
                                        <td class="left-align" rowspan="{{ $rowspanType }}">{{ $type }}</td>
                                        @php $firstTypeRow = false; @endphp
                                    @endif

                                    <td class="left-align">{{ $order['yard_name'] }}</td>

                                    <td>
                                        @foreach ($order['times'] as $time)
                                            {{ $time }}<br>
                                        @endforeach
                                    </td>

                                    <td>
                                        @foreach ($order['price_per_slot'] ?? [] as $price)
                                            {{ number_format($price) }}đ<br>
                                        @endforeach
                                    </td>

                                    <td>{{ $order['notes'] ?? 'Không có' }}</td>
                                </tr>
                                @php $totalAmount += $order['price'] ?? 0; @endphp
                            @endforeach
                        @endforeach
                    @endforeach
                @empty
                    <tr><td colspan="8">Không có đơn đặt sân nào.</td></tr>
                @endforelse
            </tbody>

            @if(count($orders) > 0)
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align: right;"><strong>Tổng tiền:</strong></td>
                    <td colspan="2"><strong>{{ number_format($totalAmount) }}đ</strong></td>
                </tr>
            </tfoot>
            @endif
        </table>

        @if (count($orders) > 0)
        <div class="pay-upload">
            <p>* LƯU Ý: Nếu bạn muốn thanh toán trước<br><br>
                Chuyển khoản ĐÚNG số tiền ở phần "Tổng tiền"<br><br>
                Nội dung chuyển khoản: TÊN + SĐT<br><br>
                Sau khi hoàn tất, chụp lại màn hình giao dịch và gửi ảnh bên dưới</p>

            <form action="{{ route('pay.upload') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png"><br><br>
                <div class="pay-btn">
                    <button type="submit" class="order-football-btn">Xác nhận đặt sân</button>
                </div>
            </form>
        </div>
        @endif
    </div>

    <div class="clear"></div>
</div>
@endsection
