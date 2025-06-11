@extends('layouts.admin')

@section('title', 'Danh sĂ¡ch loáº¡i sĂ¢n')

@section('content')
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o -->
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

    <h3>Danh sĂ¡ch loáº¡i sĂ¢n thá»ƒ thao</h3>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="{{ route('quan-ly-loai-san') }}">
                <label for="type_id">Chá»n loáº¡i sĂ¢n:</label>
                <select id="type_id" name="type_id">
                    <option value="">Táº¥t cáº£</option>
                    @foreach($allTypes as $type)
                        <option value="{{ $type->type_id }}" {{ request('type_id') == $type->type_id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                <button class="admin-search-btn" type="submit">TĂ¬m kiáº¿m</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a href="{{ route('them-loai-san') }}">ThĂªm loáº¡i sĂ¢n má»›i</a>
        </div>
    </div>
    
    <!-- Hiá»ƒn thá»‹ báº£ng dá»¯ liá»‡u -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>TĂªn loáº¡i sĂ¢n</th>
                <th colspan="2">TĂ¹y chá»n</th>
            </tr>
        </thead>
        <tbody>
            @foreach($types as $key => $type)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $type->name }}</td>
                    <td>
                        <form method="GET" action="{{ route('cap-nhat-loai-san', ['type_id' => $type->type_id]) }}">
                            <button type="submit" class="update-btn">Sá»­a</button>
                        </form>
                    </td>                                      
                    <td>
                        <form method="POST" action="{{ route('xoa-loai-san', $type->type_id) }}" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c cháº¯n muá»‘n xĂ³a loáº¡i sĂ¢n nĂ y khĂ´ng?')">
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
