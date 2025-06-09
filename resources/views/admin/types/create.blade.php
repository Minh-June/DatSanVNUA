@extends('layouts.admin')

@section('title', 'Thêm loại sân')

@section('content')
    <!-- Hiển thị thông báo -->
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

    <h3>Thêm loại sân</h3>

    <!-- Form thêm loại sân mới -->
    <div class="adminedit">
        <form action="{{ route('luu-loai-san') }}" method="POST">
            @csrf <!-- Thêm CSRF token -->
            <label for="name">Tên loại sân:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <button class="update-btn" type="submit">Lưu thông tin loại sân</button>
        </form>
    </div>
@endsection
