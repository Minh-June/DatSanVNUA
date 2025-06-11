@extends('layouts.client.account')

@section('title', 'Thay đổi mật khẩu')

@section('content')  
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

<<<<<<< HEAD
=======
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                alert("{{ $error }}");
            @endforeach
        </script>
    @endif

>>>>>>> 80d6e7c (Cập nhật giao diện)
    <h3>Thay đổi mật khẩu</h3> 

    <div class="adminedit">
        <form method="POST" action="{{ route('cap-nhat-mat-khau') }}">
            @csrf
            <label>Mật khẩu hiện tại:</label>
            <input type="password" name="matkhau_hientai" required><br><br>

            <label>Nhập mật khẩu mới:</label>
            <input type="password" name="matkhau_moi" required><br><br>

            <label>Xác nhận mật khẩu mới:</label>
            <input type="password" name="xacnhan_matkhau" required><br><br>

            <button class="update-btn" type="submit">Cập nhật mật khẩu</button>
        </form>
    </div>
@endsection
