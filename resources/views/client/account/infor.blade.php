@extends('layouts.client.account')

@section('title', 'Quáº£n lĂ½ thĂ´ng tin cĂ¡ nhĂ¢n')

@section('content')  
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                alert("{{ $error }}");
            @endforeach
        </script>
    @endif

    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <h3>Quáº£n lĂ½ thĂ´ng tin cĂ¡ nhĂ¢n</h3>

    <div class="adminedit">
        <form method="post" action="{{ route('cap-nhat-thong-tin-ca-nhan') }}">
            @csrf

            <div class="form-group">
                <label for="fullname">Há» vĂ  tĂªn:</label>
                <input type="text" name="fullname" value="{{ $user->fullname }}" required>
            </div>

            <div class="form-group">
                <label for="gender">Giá»›i tĂ­nh:</label>
                <select name="gender" required>
                    <option value="Nam" {{ $user->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Ná»¯" {{ $user->gender == 'Ná»¯' ? 'selected' : '' }}>Ná»¯</option>
                    <option value="KhĂ¡c" {{ $user->gender == 'KhĂ¡c' ? 'selected' : '' }}>KhĂ¡c</option>
                </select>
            </div>

            <div class="form-group">
                <label for="birthdate">NgĂ y sinh:</label>
                <input type="date" name="birthdate" value="{{ $user->birthdate }}" required>
            </div>

            <div class="form-group">
                <label for="phonenb">Sá»‘ Ä‘iá»‡n thoáº¡i:</label>
                <input type="text" name="phonenb" value="{{ $user->phonenb }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="{{ $user->email }}" required>
            </div>

            <button class="update-btn" type="submit">Cáº­p nháº­t thĂ´ng tin cĂ¡ nhĂ¢n</button>
        </form>
    </div>

@endsection
