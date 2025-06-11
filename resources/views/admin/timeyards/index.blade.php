@extends('layouts.admin')

@section('title', 'Quáº£n lĂ½ khung giá» cho thuĂª')

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

    <h3>Quáº£n lĂ½ khung giá» - {{ $times->first()->yard->name ?? '' }}</h3>

    <div class="admin-top-bar">
        @if(request('yard_id'))
            <div class="admin-search">
                <form method="GET" action="{{ route('quan-ly-thoi-gian-san') }}">
                    <input type="hidden" name="yard_id" value="{{ request('yard_id') }}">
                    <label for="date">Chá»n ngĂ y:</label>
                    <input type="date" id="date" name="date" value="{{ request('date', date('Y-m-d')) }}">
                    <button class="admin-search-btn" type="submit">TĂ¬m kiáº¿m</button>
                </form>
            </div>
        @endif
        <div class="admin-add-btn">
            <a href="{{ route('them-thoi-gian-san') }}">ThĂªm khung giá» cho thuĂª</a>
        </div>
    </div>

        <!-- Hiá»ƒn thá»‹ báº£ng dá»¯ liá»‡u khi Ä‘Ă£ chá»n sĂ¢n vĂ  lá»c theo ngĂ y -->
        <table id='ListCustomers'>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Khung giá»</th>
                    <th>GiĂ¡ (VNÄ)</th>
                    <th colspan="2">TĂ¹y chá»n</th>
                </tr>
            </thead>
            <tbody>
                @foreach($times as $index => $time)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $time->time }}</td>
                        <td>{{ number_format($time->price, 0, ',', '.') }}</td>
                        <td>
                            <form method="GET" action="{{ route('cap-nhat-thoi-gian-san', ['time_id' => $time->time_id]) }}">
                                <button type="submit" class="update-btn">Sá»­a</button>
                            </form>
                        </td>
                        <td>
                        <form method="POST" action="{{ route('xoa-thoi-gian-san', ['time_id' => $time->time_id, 'yard_id' => request('yard_id')]) }}" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c cháº¯n muá»‘n xĂ³a khung giá» nĂ y?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="update-btn">XĂ³a</button>
                        </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
