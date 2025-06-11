@extends('layouts.client.account')

@section('title', 'Thay Ã„â€˜Ã¡Â»â€¢i mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u')

@section('content')  
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                alert("{{ $error }}");
            @endforeach
        </script>
    @endif

    <h3>Thay Ã„â€˜Ã¡Â»â€¢i mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u</h3> 

    <div class="adminedit">
        <form method="POST" action="{{ route('cap-nhat-mat-khau') }}">
            @csrf
            <label>MÃ¡ÂºÂ­t khÃ¡ÂºÂ©u hiÃ¡Â»â€¡n tÃ¡ÂºÂ¡i:</label>
            <input type="password" name="matkhau_hientai" required><br><br>

            <label>NhÃ¡ÂºÂ­p mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u mÃ¡Â»â€ºi:</label>
            <input type="password" name="matkhau_moi" required><br><br>

            <label>XÄ‚Â¡c nhÃ¡ÂºÂ­n mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u mÃ¡Â»â€ºi:</label>
            <input type="password" name="xacnhan_matkhau" required><br><br>

            <button class="update-btn" type="submit">CÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u</button>
        </form>
    </div>
@endsection
