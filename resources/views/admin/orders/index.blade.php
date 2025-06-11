@extends('layouts.admin')

@section('title', 'Quáº£n lĂ½ Ä‘Æ¡n Ä‘áº·t sĂ¢n thá»ƒ thao')

@section('content')
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <h3>Danh sĂ¡ch Ä‘Æ¡n sĂ¢n thá»ƒ thao</h3>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="{{ route('quan-ly-don-dat-san') }}">
                <label for="selected_date">Chá»n ngĂ y:</label>
                <input type="date" id="selected_date" name="selected_date" value="{{ request('selected_date') }}">
                <button class="admin-search-btn" type="submit">TĂ¬m kiáº¿m</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a href="{{ route('them-don-dat-san') }}">ThĂªm Ä‘Æ¡n Ä‘áº·t sĂ¢n</a>
        </div>
    </div>

    @if($orders->count() > 0)
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>NgĂ y táº¡o</th>
                <th>Há» vĂ  tĂªn</th>
                <th>SÄT</th>
                <!-- <th>TĂªn sĂ¢n</th>
                <th>NgĂ y thuĂª</th>
                <th>Thá»i gian</th>
                <th>Ghi chĂº</th> -->
                <th>ThĂ nh tiá»n</th>
                <th>áº¢nh thanh toĂ¡n</th>
                <th>ThĂ´ng tin</th>
                <th colspan="2">TĂ¹y chá»n</th>
            </tr>

            @foreach($orders as $key => $order)
                @php
                    $rowspan = $order->groupedDetails->count();
                @endphp

                @foreach($order->groupedDetails as $index => $detail)
                    <tr>
                        @if($index === 0)
                            <td rowspan="{{ $rowspan }}">{{ $key + 1 }}</td>
                            <td rowspan="{{ $rowspan }}">                
                                {{ \Carbon\Carbon::parse($order->date)->format('d/m/Y') }}<br>
                                {{ \Carbon\Carbon::parse($order->date)->format('H:i') }}
                            </td>
                            <td rowspan="{{ $rowspan }}">{{ $order->name }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $order->phone }}</td>
                        @endif

                        <!-- <td>{{ $detail['yard']->name ?? 'KhĂ´ng xĂ¡c Ä‘á»‹nh' }}</td>
                        <td>{{ \Carbon\Carbon::parse($detail['date'])->format('d/m/Y') }}</td>
                        <td>{{ $detail['times'] }}</td>
                        <td>{{ $detail['notes'] ?: 'KhĂ´ng cĂ³' }}</td> -->

                        @if($index === 0)
                            <td rowspan="{{ $rowspan }}">
                                {{ number_format($order->orderDetails->sum('price'), 0, ',', '.') }} VND
                            </td>
                        @endif

                        @if($index === 0)
                            <td rowspan="{{ $rowspan }}">
                                @php $images = json_decode($order->image); @endphp
                                @if ($images && count($images) > 0)
                                    @foreach ($images as $img)
                                        <img src="{{ asset('storage/' . $img) }}" alt="áº¢nh" style="width:100px; height:200px; cursor:pointer;" onclick="showImage(this.src)">
                                    @endforeach
                                @else
                                    KhĂ´ng cĂ³
                                @endif
                            </td>
                            
                            <td rowspan="{{ $rowspan }}">
                                <a href="{{ route('cap-nhat-don-dat-san', $order->order_id) }}">Xem chi tiáº¿t</a>
                            </td>

                            <td rowspan="{{ $rowspan }}">
                                <form method="POST" action="{{ route('cap-nhat-trang-thai-don-dat-san', $order->order_id) }}">
                                    @csrf
                                    <select name="status">
                                        <option value="0" {{ $order->status == 0 ? 'selected' : '' }}>Chá» xĂ¡c nháº­n</option>
                                        <option value="1" {{ $order->status == 1 ? 'selected' : '' }}>XĂ¡c nháº­n</option>
                                        <option value="2" {{ $order->status == 2 ? 'selected' : '' }}>Há»§y</option>
                                    </select><br>
                                    <button type="submit" class="update-btn">Cáº­p nháº­t</button>
                                </form>
                            </td>

                            <td rowspan="{{ $rowspan }}">
                                <form method="POST" action="{{ route('xoa-don-dat-san', $order->order_id) }}" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c muá»‘n xĂ³a Ä‘Æ¡n nĂ y?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="update-btn">XĂ³a</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </table>
    @else
        <p>KhĂ´ng cĂ³ káº¿t quáº£</p>
    @endif
@endsection

