@extends('layouts.client.account')

@section('title', 'Quản lý thông tin cá nhân')

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

    <h2>Quản lý thông tin cá nhân</h2>

    <div class="adminedit">
        <form method="post" action="{{ route('cap-nhat-thong-tin-ca-nhan') }}">
            @csrf

            <div class="adminedit-form-group">
                <label for="fullname">Họ và tên:</label>
                <input type="text" name="fullname" value="{{ $user->fullname }}" required>
            </div>

            <div class="adminedit-form-group">
                <label for="gender">Giới tính:</label>
                <select name="gender" required>
                    <option value="Nam" {{ $user->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                    <option value="Nữ" {{ $user->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                    <option value="Khác" {{ $user->gender == 'Khác' ? 'selected' : '' }}>Khác</option>
                </select>
            </div>

            <div class="adminedit-form-group">
                <label for="birthdate">Ngày sinh:</label>
                <input type="date" name="birthdate" value="{{ $user->birthdate }}" required>
            </div>

            <div class="adminedit-form-group">
                <label for="phonenb">Số điện thoại:</label>
                <input type="text" name="phonenb" value="{{ $user->phonenb }}" required>
            </div>

            <div class="adminedit-form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="{{ $user->email }}" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Cập nhật thông tin</button>
            </div>
        </form>
    </div>

@endsection
