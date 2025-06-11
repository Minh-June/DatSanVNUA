@extends('layouts.client.client')

@section('title', 'Há»£p Ä‘á»“ng')

@section('content')
<div id="content" class="order-section">
    <h2 class="order-heading">XĂ¡c nháº­n thĂ´ng tin Ä‘áº·t sĂ¢n</h2>

    <div class="order-successfully">
        <div class="order-successfully-infor">
            <h3 class="order-successfully-header">Há»£p Ä‘á»“ng Ä‘áº·t sĂ¢n</h3>

            <h3>Äiá»u 1: Ná»™i dung há»£p Ä‘á»“ng</h3><br>
            <p>BĂªn A cam káº¿t vĂ  thá»±c hiá»‡n Ä‘áº·t lá»‹ch sĂ¢n thá»ƒ thao theo cĂ¡c thĂ´ng tin sau Ä‘Ă¢y:</p><br>

            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>NgĂ y Ä‘áº·t</th>
                        <th>Há» vĂ  tĂªn</th>
                        <th>SÄT</th>
                        <th>TĂªn sĂ¢n</th>
                        <th>Thá»i gian thuĂª</th>
                        <!-- <th>GiĂ¡ tá»«ng khung giá»</th> -->
                        <th>Ghi chĂº</th>
                        <th>ThĂ nh tiá»n</th>
                        <th>Thao tĂ¡c</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('orders', []) as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ date('d/m/Y', strtotime($order['date'])) }}</td>
                            @if ($index === 0)
                                <td rowspan="{{ count(session('orders')) }}">{{ $order['name'] }}</td>
                                <td rowspan="{{ count(session('orders')) }}">{{ $order['phone'] }}</td>
                            @endif
                            <td>{{ $order['yard_name'] }}</td>
                            <td>
                                @foreach($order['times'] as $time)
                                    <div>{{ $time }}</div>
                                @endforeach
                            </td>
                            <!-- <td>
                                @foreach($order['times'] as $key => $time)
                                    <div>{{ number_format($order['price_per_slot'][$key] ?? 0) }} VND</div>
                                @endforeach
                            </td> -->
                            <td>{{ $order['notes'] ?? 'KhĂ´ng cĂ³' }}</td>
                            <td>{{ number_format($order['price']) }} VND</td>
                            <td>
                                <form action="{{ route('xoa-don-tam-thoi') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="index" value="{{ $index }}">
                                    <button type="submit" class="update-btn">XĂ³a</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

            <h3>Äiá»u 2: Thanh toĂ¡n</h3><br>
            <p>BĂªn A cam káº¿t thanh toĂ¡n phĂ­ dá»‹ch vá»¥ Ä‘áº·t lá»‹ch theo thá»a thuáº­n giá»¯a hai bĂªn.</p><br>

            <h3>Äiá»u 3: Äiá»u khoáº£n chung</h3><br>
            <p>Cáº£ hai bĂªn cam káº¿t thá»±c hiá»‡n Ä‘Ăºng vĂ  Ä‘áº§y Ä‘á»§ cĂ¡c Ä‘iá»u khoáº£n trong há»£p Ä‘á»“ng nĂ y.</p>
            <p>Há»£p Ä‘á»“ng cĂ³ giĂ¡ trá»‹ tá»« ngĂ y kĂ½ vĂ  cĂ³ thá»ƒ Ä‘Æ°á»£c Ä‘iá»u chá»‰nh hoáº·c cháº¥m dá»©t khi hai bĂªn Ä‘á»“ng Ă½.</p><br>

            <h3>Äiá»u 4: KĂ­ vĂ  xĂ¡c nháº­n</h3><br>
            <p class="order-successfully-day">HĂ  Ná»™i, ngĂ y {{ date('d/m/Y') }}</p><br>

            <div class="signature">
                <div class="signature-left">
                    <p>BĂªn A<br><br> {{ session('orders.0.name') }}</p>
                </div>
                <div class="signature-right">
                    <p>BĂªn B<br><br> Group 48</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-link">
        <a href="{{ route('thanh-toan') }}" class="order-football-btn">Tiáº¿p tá»¥c</a>
    </div>

</div>
<div class="clear"></div>
@endsection
