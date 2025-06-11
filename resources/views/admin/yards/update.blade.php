@extends('layouts.admin')

@section('title', 'SÃ¡Â»Â­a thÄ‚Â´ng tin sÄ‚Â¢n')

@section('content')
    <!-- HiÃ¡Â»Æ’n thÃ¡Â»â€¹ thÄ‚Â´ng bÄ‚Â¡o -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <!-- HiÃ¡Â»Æ’n thÃ¡Â»â€¹ thÄ‚Â´ng bÄ‚Â¡o lÃ¡Â»â€”i -->
    @if ($errors->any())
        <script>
            alert("{{ $errors->first() }}");
        </script>
    @endif

    <h3>SÃ¡Â»Â­a thÄ‚Â´ng tin sÄ‚Â¢n</h3>

    <!-- Form chÃ¡Â»â€°nh sÃ¡Â»Â­a thÄ‚Â´ng tin sÄ‚Â¢n -->
    <div class="adminedit">
        <form method="POST" action="{{ route('update.yard', $yard->yard_id) }}">
            @csrf
            <input type="hidden" name="_method" value="POST">

            <label for="type_id">ThÃ¡Â»Æ’ loÃ¡ÂºÂ¡i sÄ‚Â¢n:</label>
            <select id="type_id" name="type_id" required>
                @foreach($types as $type)
                    <option value="{{ $type->type_id }}" {{ $yard->type_id == $type->type_id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            <br>

            <label for="name">TÄ‚Âªn sÄ‚Â¢n:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $yard->name) }}" required>
            <br>

            <button class="update-btn" type="submit">CÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t thÄ‚Â´ng tin sÄ‚Â¢n</button>
        </form>
    </div>
@endsection
