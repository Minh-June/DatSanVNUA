@extends('layouts.client.user')

@section('title', 'Thay đổi mật khẩu')

@section('content')  
    <!-- Hiển thị thông báo thành công -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <!-- Hiển thị thông báo lỗi -->
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

                        <h3>Thay đổi mật khẩu</h3> 

                        <div class="adminedit">
                            <form method="post" action="{{ route('thay-doi-mat-khau') }}">
                                @csrf
                                <label for="matkhau_hientai">Mật khẩu hiện tại:</label>
                                <input type="password" name="matkhau_hientai" required><br><br>
                            
                                <label for="matkhau_moi">Mật khẩu mới:</label>
                                <input type="password" name="matkhau_moi" required><br><br>
                            
                                <label for="xacnhan_matkhau">Xác nhận mật khẩu mới:</label>
                                <input type="password" name="xacnhan_matkhau" required><br><br>
                            
                                <button class="update-btn" type="submit">Cập nhật mật khẩu mới</button>
                            </form>                            
                        </div>
@endsection