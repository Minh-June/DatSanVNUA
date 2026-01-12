@extends('layouts.client.account')

@section('title', 'Quản lý thông tin cá nhân')

@section('content')  
    @php
        use Carbon\Carbon;
        $maxDate = Carbon::now()->subYears(13)->format('Y-m-d');
        $minDate = Carbon::now()->subYears(100)->format('Y-m-d');
    @endphp

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                alert("{{ $error }}");
            @endforeach
        </script>
    @endif

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    <h2>Quản lý thông tin cá nhân</h2>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            @if($user->image)
                <form method="POST" action="{{ route('xoa-anh-dai-dien') }}" onsubmit="return confirm('Bạn có chắc muốn xóa ảnh đại diện không ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">
                        <i class="fa-solid fa-xmark"></i>
                        Xóa ảnh đại diện
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="adminedit">
        <form method="post" action="{{ route('cap-nhat-thong-tin-ca-nhan') }}" enctype="multipart/form-data">
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
                <input type="date"
                       name="birthdate"
                       value="{{ old('birthdate', $user->birthdate) }}"
                       min="{{ $minDate }}"
                       max="{{ $maxDate }}"
                       required>
            </div>

            <div class="adminedit-form-group">
                <label for="phonenb">Số điện thoại:</label>
                <input type="text" name="phonenb" value="{{ $user->phonenb }}" required>
            </div>

            <div class="adminedit-form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="{{ $user->email }}" required>
            </div>

            @if($user->role == 0)
                <div class="adminedit-form-group">
                    <label for="www">Website:</label>
                    <input type="url" name="www" value="{{ $user->www ?? '' }}" placeholder="https://example.com" required>
                </div>
            @endif

            <div class="adminedit-form-group"> 
                <p>Ảnh đại diện:</p> 
                @if($user->image)
                    <img src="{{ asset('storage/' . $user->image) }}" alt="Avatar" width="120" style="border-radius: 8px; border: 1px solid #ccc;"> 
                @else
                    <p style="margin:0 0 20px 100px;">Hiện chưa có</p>
                @endif
            </div>

            <div class="adminedit-form-group">
                <label for="image">Cập nhật ảnh:</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="adminedit-button" style="margin-bottom:80px;">
                <button class="update-btn" type="submit">Cập nhật thông tin</button>
            </div>
        </form>
    </div>
@endsection
