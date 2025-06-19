@extends('layouts.admin')

@section('title', 'Danh sách người dùng')

@section('content')
    <!-- Hiển thị thông báo -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    @if ($errors->any())
        <script>
            alert("{{ $errors->first('keyword') }}");
        </script>
    @endif

    <h2>{{ isset($xem_user) ? 'Thông tin người dùng' : 'Danh sách người dùng' }}</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            @if(!isset($xem_user))
                <form method="GET" action="{{ route('quan-ly-nguoi-dung') }}">
                    <input
                        type="text"
                        name="keyword"
                        placeholder="Nhập thông tin"
                        value="{{ request('keyword') }}"
                        required
                        pattern="^[\p{L}0-9\s]+$"
                        title="Chỉ nhập chữ cái, số và khoảng trắng"
                    />
                    <button class="update-btn" type="submit">Tìm kiếm</button>
                </form>
            @endif
        </div>

        <div class="admin-add-btn">
            @if(isset($xem_user))
                <a class="delete-btn"
                href="{{ route('reset-mat-khau-nguoi-dung', ['user_id' => $xem_user->user_id]) }}"
                onclick="return confirm('Bạn có chắc chắn muốn đặt lại mật khẩu người dùng này không?')">
                <i class="fa-solid fa-rotate-left"></i> Đặt lại mật khẩu
                </a>
            @else
                <a class="update-btn" href="{{ route('dang-ky') }}">Thêm người dùng</a>
            @endif
        </div>
    </div>

    @if(isset($xem_user))
        <!-- Hiển thị thông tin người dùng -->
        <div class="adminedit">
            <form>
                @csrf
                <div class="adminedit-form-group">
                    <label for="fullname">Họ và tên:</label>
                    <input type="text" name="fullname" value="{{ $xem_user->fullname }}" disabled>
                </div>

                <div class="adminedit-form-group">
                    <label for="gender">Giới tính:</label>
                    <input type="text" name="gender" value="{{ $xem_user->gender }}" disabled>
                </div>

                <div class="adminedit-form-group">
                    <label for="birthdate">Ngày sinh:</label>
                    <input type="date" name="birthdate" value="{{ $xem_user->birthdate }}" disabled>
                </div>

                <div class="adminedit-form-group">
                    <label for="phonenb">Số điện thoại:</label>
                    <input type="text" name="phonenb" value="{{ $xem_user->phonenb }}" disabled>
                </div>
                
                <div class="adminedit-form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="{{ $xem_user->email }}" disabled>
                </div>
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
                    <th>SĐT</th>
                    <th>Tên tài khoản</th>
                    <th>Thông tin</th>
                    <th>Vai trò</th>
                    <th>Tuỳ chọn</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="left-align">{{ $user->fullname }}</td>
                    <td>{{ $user->phonenb }}</td>
                    <td>{{ $user->username }}</td>
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
                        <form method="POST" action="{{ route('xoa-nguoi-dung', ['user_id' => $user->user_id]) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xoá người dùng này không?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
