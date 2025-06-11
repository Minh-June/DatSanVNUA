@extends('layouts.admin')

@section('title', 'Quáº£n lĂ½ hĂ¬nh áº£nh sĂ¢n')

@section('content')
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o thĂ nh cĂ´ng -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o lá»—i -->
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <h3>
        Quáº£n lĂ½ hĂ¬nh áº£nh 
        @if(isset($selectedYard))
            - {{ $selectedYard->name }}
        @endif
    </h3>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            <a href="{{ route('them-hinh-anh-san') }}">ThĂªm hĂ¬nh áº£nh sĂ¢n</a>
        </div>
    </div>

    <!-- Hiá»ƒn thá»‹ báº£ng hĂ¬nh áº£nh khi Ä‘Ă£ chá»n sĂ¢n -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>HĂ¬nh áº£nh</th>
                <th colspan="2">TĂ¹y chá»n</th>
            </tr>
        </thead>
        <tbody>
            @foreach($selectedYard->images as $index => $image)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $image->image) }}" alt="HĂ¬nh áº£nh" class="admin-image">
                    </td>
                    <td>
                        <form action="{{ route('cap-nhat-hinh-anh-san', ['image_id' => $image->image_id]) }}" method="GET">
                            <button type="submit" class="update-btn">Sá»­a</button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('xoa-hinh-anh-san', ['image_id' => $image->image_id]) }}" method="POST" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c cháº¯n muá»‘n xĂ³a hĂ¬nh áº£nh nĂ y?');">
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
