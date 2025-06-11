@extends('layouts.admin')

@section('title', 'ThÄ‚Âªm loÃ¡ÂºÂ¡i sÄ‚Â¢n')

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

    <h3>ThÄ‚Âªm loÃ¡ÂºÂ¡i sÄ‚Â¢n</h3>

    <!-- Form thÄ‚Âªm loÃ¡ÂºÂ¡i sÄ‚Â¢n mÃ¡Â»â€ºi -->
    <div class="adminedit">
        <form action="{{ route('luu-loai-san') }}" method="POST">
            @csrf <!-- ThÄ‚Âªm CSRF token -->
            <label for="name">TÄ‚Âªn loÃ¡ÂºÂ¡i sÄ‚Â¢n:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <button class="update-btn" type="submit">LÃ†Â°u thÄ‚Â´ng tin loÃ¡ÂºÂ¡i sÄ‚Â¢n</button>
        </form>
    </div>
@endsection
