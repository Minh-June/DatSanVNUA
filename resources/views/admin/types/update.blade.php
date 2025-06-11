@extends('layouts.admin') 

@section('title', 'SÃ¡Â»Â­a thÄ‚Â´ng tin loÃ¡ÂºÂ¡i sÄ‚Â¢n')

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


    <h3>SÃ¡Â»Â­a thÄ‚Â´ng tin loÃ¡ÂºÂ¡i sÄ‚Â¢n</h3>

    <!-- Form chÃ¡Â»â€°nh sÃ¡Â»Â­a thÄ‚Â´ng tin loÃ¡ÂºÂ¡i sÄ‚Â¢n -->
    <div class="adminedit">
        <form method="POST" action="{{ route('update.type', $type->type_id) }}">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <label for="name">TÄ‚Âªn loÃ¡ÂºÂ¡i sÄ‚Â¢n:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $type->name) }}" required>
            <br>
            <button class="update-btn" type="submit">CÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t thÄ‚Â´ng tin loÃ¡ÂºÂ¡i sÄ‚Â¢n</button>
        </form>
    </div>
@endsection
