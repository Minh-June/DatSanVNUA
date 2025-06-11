@extends('layouts.client.account')

@section('title', 'Lá»‹ch sá»­ Ä‘áº·t sĂ¢n')

@section('content') 
    <h3>Danh sĂ¡ch Ä‘Æ¡n Ä‘áº·t sĂ¢n</h3>  
    
    <!-- Begin: Date Filter -->
    <div class="admin-search">
        <form method="GET" action="{{ route('thong-tin-tai-khoan') }}">
            <label for="date">Chá»n ngĂ y:</label>
            <input type="date" id="date" name="date" value="{{ request('date', date('Y-m-d')) }}">
            <button class="update-btn" type="submit">TĂ¬m kiáº¿m</button>
        </form>
    </div>        
    <!-- End: Date Filter -->

    <!-- Begin: Display Orders -->
    @if($orders->count() > 0)
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>NgĂ y Ä‘áº·t</th>
                <!-- <th>Há» vĂ  tĂªn</th>
                <th>Sá»‘ Ä‘iá»‡n thoáº¡i</th> -->
                <th>NgĂ y thuĂª</th>
                <th>TĂªn sĂ¢n</th>
                <th>Thá»i gian</th>
                <!-- <th>GiĂ¡ tá»«ng khung giá»</th> -->
                <th>ThĂ nh tiá»n</th>
                <th>Ghi chĂº</th>
                <th>áº¢nh thanh toĂ¡n</th>
                <th>Tráº¡ng thĂ¡i</th>
            </tr>
            @php $index = 1; @endphp
            @foreach($orders as $order)
                @php
                    $totalDetailsCount = $order->orderDetails->count();
                    $groupedDetails = $order->orderDetails->groupBy(function($item) {
                        return $item->yard_id . '_' . $item->date;
                    });
                    $orderRowspan = $totalDetailsCount;
                @endphp

                @foreach($groupedDetails as $group)
                    @php
                        $firstDetail = $group->first();
                        $timeString = $group->pluck('time')->implode('<br>');
                    @endphp

                    <tr>
                        @if ($loop->parent->first)
                            <td rowspan="{{ $orderRowspan }}">{{ $index++ }}</td>
                            <td rowspan="{{ $orderRowspan }}">
                                {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
                                {{ \Carbon\Carbon::parse($order->date)->format('H:i') }}
                            </td>
                        @endif

                        <td>{{ \Carbon\Carbon::parse($firstDetail->date)->format('d/m/Y') }}</td>
                        <td>{{ $firstDetail->yard ? $firstDetail->yard->name : 'KhĂ´ng xĂ¡c Ä‘á»‹nh' }}</td>
                        <td>{!! $timeString !!}</td>

                        @if ($loop->parent->first)
                            <td rowspan="{{ $orderRowspan }}">
                                {{ number_format($order->orderDetails->sum('price'), 0, ',', '.') }}Ä‘
                            </td>
                            <td rowspan="{{ $orderRowspan }}">{{ $firstDetail->notes ?? 'KhĂ´ng cĂ³' }}</td>
                            <td rowspan="{{ $orderRowspan }}">
                                @php
                                    $images = json_decode($order->image) ?: [];
                                @endphp
                                @if(count($images) > 0)
                                    @foreach($images as $img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="áº¢nh thanh toĂ¡n" style="width:100px; height:200px; cursor: pointer;" onclick="showImage('{{ asset('storage/' . $img) }}')">
                                    @endforeach
                                @else
                                    KhĂ´ng cĂ³ áº£nh
                                @endif
                            </td>
                            <td rowspan="{{ $orderRowspan }}">
                                @switch($order->status)
                                    @case(0) Chá» xĂ¡c nháº­n @break
                                    @case(1) ÄĂ£ xĂ¡c nháº­n @break
                                    @case(2) ÄÆ¡n Ä‘Ă£ bá»‹ há»§y @break
                                    @default KhĂ´ng xĂ¡c Ä‘á»‹nh
                                @endswitch
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </table>
    @else
        <h3 style="font-weight: normal; font-size: 18px;">Hiá»‡n táº¡i báº¡n chÆ°a cĂ³ Ä‘Æ¡n Ä‘áº·t sĂ¢n nĂ o</h3>
    @endif
    <!-- End: Display Orders -->
@endsection
