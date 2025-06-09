@extends('layouts.client.account')

@section('title', 'Quản lý thông tin cá nhân')

@section('content')  
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <h3>Quản lý thông tin cá nhân</h3>

    <div class="adminedit">
        <form method="post" action="{{ route('cap-nhat-thong-tin-ca-nhan') }}">
            @csrf <!-- Thêm token CSRF -->
            <label for="fullname">Họ và tên:</label>
            <input type="text" name="fullname" value="{{ $user->fullname ?? '' }}" required><br>
            
            <label for="gender">Giới tính:</label>
            <select class="admin-time-select" name="gender" required>
                <option value="Nam" {{ $user->gender == 'Nam' ? 'selected' : '' }}>Nam</option>
                <option value="Nữ" {{ $user->gender == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                <option value="Khác" {{ $user->gender == 'Khác' ? 'selected' : '' }}>Khác</option>
            </select><br>
            
            <label for="birthdate">Ngày sinh:</label>
            <input type="date" name="birthdate" value="{{ $user->birthdate ?? '' }}" required><br>
            
            <label for="phonenb">Số điện thoại:</label>
            <input type="text" name="phonenb" value="{{ $user->phonenb ?? '' }}" required><br>
            
            <label for="email">Email:</label>
            <input type="email" name="email" value="{{ $user->email ?? '' }}" required><br>
            
            <button class="update-btn" type="submit">Cập nhật thông tin cá nhân</button>
        </form>                          
    </div>
@endsection
