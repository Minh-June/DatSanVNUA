@extends('layouts.admin')

@section('title', 'Danh sách người dùng')

@section('content')
    <!-- Hiển thị thông báo -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <h3>{{ isset($xem_user) ? 'Thông tin người dùng' : 'Danh sách người dùng' }}</h3>

    <!-- Thanh top-bar luôn hiển thị -->
    <div class="admin-top-bar">
        <div class="admin-search">
            @if(!isset($xem_user))
            <form method="GET" action="{{ route('quan-ly-nguoi-dung') }}">
                <label for="type_id">Tìm người dùng:</label>
                <input type="text" name="keyword" placeholder="Nhập thông tin cần tìm" value="{{ request('keyword') }}">
                <button class="admin-search-btn" type="submit">Tìm kiếm</button>
            </form>
            @endif
        </div>

        <div class="admin-add-btn">
            @if(isset($xem_user))
                <a href="{{ route('quan-ly-nguoi-dung') }}">Quay lại danh sách</a>
            @else
                <a href="{{ route('dang-ky') }}">Thêm người dùng mới</a>
            @endif
        </div>
    </div>

    @if(isset($xem_user))
        <!-- Hiển thị thông tin người dùng -->
        <div class="adminedit">
            <form>
                @csrf
                <label for="fullname">Họ và tên:</label>
                <input type="text" name="fullname" value="{{ $xem_user->fullname }}" disabled><br>

                <label for="gender">Giới tính:</label>
                <input type="text" name="gender" value="{{ $xem_user->gender }}" disabled><br>

                <label for="birthdate">Ngày sinh:</label>
                <input type="date" name="birthdate" value="{{ $xem_user->birthdate }}" disabled><br>

                <label for="phonenb">Số điện thoại:</label>
                <input type="text" name="phonenb" value="{{ $xem_user->phonenb }}" disabled><br>

                <label for="email">Email:</label>
                <input type="email" name="email" value="{{ $xem_user->email }}" disabled><br>
            </form>                          
        </div>
        <br>
    @else
        <!-- Hiển thị bảng dữ liệu -->
        <table id='ListCustomers'>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Họ và tên</th>
                    <th>Tên tài khoản</th>
                    <th>Vai trò</th>
                    <th>Thông tin</th>
                    <th colspan="2">Tùy chọn</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->fullname }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->role == 0 ? 'Admin' : 'Khách hàng' }}</td>
                    <td>
                        <a href="{{ route('quan-ly-nguoi-dung', ['xem' => $user->user_id]) }}">Xem chi tiết</a>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('cap-nhat-vai-tro-nguoi-dung', $user->user_id) }}">
                            @csrf
                            <select name="role">
                                <option value="0" {{ $user->role == 0 ? 'selected' : '' }}>Admin</option>
                                <option value="1" {{ $user->role == 1 ? 'selected' : '' }}>Khách hàng</option>
                            </select><br>
                            <button type="submit" class="update-btn">Cập nhật</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('xoa-nguoi-dung', ['user_id' => $user->user_id]) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="update-btn">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
