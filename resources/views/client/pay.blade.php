@extends('layouts.client.client')

@section('title', 'Thanh toĂ¡n')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

<div id="content" class="order-section">
    <h2 class="order-heading">THANH TOĂN</h2>

    <div class="pay-content">
        <div class="pay-information">
            <div class="bank-account">TĂ i khoáº£n ngĂ¢n hĂ ng</div>
            <div class="bank-account">TĂªn tĂ i khoáº£n: Nguyá»…n Há»¯u Quang Minh</div>
            <div class="bank-account">Sá»‘ tĂ i khoáº£n: 1903 6786 8800 12</div>
            <div class="bank-account">NgĂ¢n hĂ ng: Techcombank</div>
        </div>
        <div class="pay-information">
            <div class="bank-qr">
                MĂ£ QR <br>
                <img class="bank-qr-img" src="{{ asset('image/qr/qr.jpg') }}" alt="MĂ£ QR">
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <div class="pay-customer">
        <h3>ThĂ´ng tin Ä‘Æ¡n Ä‘áº·t sĂ¢n</h3><br>

        <table id="ListCustomers">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>NgĂ y Ä‘áº·t</th>
                    <th>Há» vĂ  tĂªn</th>
                    <th>SÄT</th>
                    <th>TĂªn sĂ¢n</th>
                    <th>Thá»i gian thuĂª</th>
                    <th>GiĂ¡ tá»«ng khung giá»</th>
                    <th>Ghi chĂº</th>
                </tr>
            </thead>
            <tbody>
                @php $totalAmount = 0; @endphp
                @forelse ($orders as $index => $order)
                    @php $totalAmount += $order['price']; @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($order['date'])->format('d/m/Y') }}</td>
                        @if ($index === 0)
                            <td rowspan="{{ count($orders) }}">{{ $order['name'] }}</td>
                            <td rowspan="{{ count($orders) }}">{{ $order['phone'] }}</td>
                        @endif
                        <td>{{ $order['yard_name'] }}</td>
                        <td>
                            @foreach ($order['times'] as $time)
                                {{ $time }}<br>
                            @endforeach
                        </td>
                        <td>
                            @if(!empty($order['price_per_slot']) && is_array($order['price_per_slot']))
                            @foreach($order['price_per_slot'] as $price)
                            {{ number_format($price) }} VND<br>
                            @endforeach
                            @else
                            KhĂ´ng cĂ³ dá»¯ liá»‡u
                            @endif
                        </td>
                        <td>{{ $order['notes'] ?? 'KhĂ´ng cĂ³ ghi chĂº' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8">KhĂ´ng cĂ³ Ä‘Æ¡n Ä‘áº·t sĂ¢n nĂ o.</td></tr>
                @endforelse
            </tbody>
            @if(count($orders) > 0)
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align: right;"><strong>Tá»•ng tiá»n:</strong></td>
                    <td colspan="2"><strong>{{ number_format($totalAmount) }} VND</strong></td>
                </tr>
            </tfoot>
            @endif
        </table>

        @if (count($orders) > 0)
        <div class="pay-upload">
            <p>* LÆ¯U Ă: Náº¿u báº¡n muá»‘n thanh toĂ¡n trÆ°á»›c<br><br>
                Chuyá»ƒn khoáº£n ÄĂNG sá»‘ tiá»n á»Ÿ pháº§n "Tá»•ng tiá»n"<br><br>
                Ná»™i dung chuyá»ƒn khoáº£n: TĂN + SÄT<br><br>
                Sau khi hoĂ n táº¥t, chá»¥p láº¡i mĂ n hĂ¬nh giao dá»‹ch vĂ  gá»­i áº£nh bĂªn dÆ°á»›i.</p>

            <form action="{{ route('pay.upload') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png"><br><br>
                <div class="pay-btn">
                    <button type="submit" class="order-football-btn">XĂ¡c nháº­n Ä‘áº·t sĂ¢n</button>
                </div>
            </form>
        </div>
        @endif
    </div>

    <div class="clear"></div>
</div>
@endsection
