@extends('layouts.client.account')

@section('title', 'Thay đổi mật khẩu')

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

    <h2>Thay đổi mật khẩu</h2> 

    <div class="adminedit">
        <form method="POST" action="{{ route('cap-nhat-mat-khau') }}">
            @csrf
            <div class="adminedit-form-group">
                <label>Mật khẩu hiện tại:</label>
                <input type="password" name="matkhau_hientai" required>
            </div>

            <div class="adminedit-form-group">
                <label>Nhập mật khẩu mới:</label>
                <input type="password" name="matkhau_moi" required>
            </div>
            
            <div class="adminedit-form-group">
                <label>Xác nhận mật khẩu mới:</label>
                <input type="password" name="xacnhan_matkhau" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Cập nhật mật khẩu</button>
            </div>
        </form>
    </div>
@endsection
