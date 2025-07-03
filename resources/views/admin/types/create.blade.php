@extends('layouts.admin')

@section('title', 'Thêm loại sân')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if ($errors->any())
        <script>alert("{{ $errors->first() }}");</script>
    @endif

    <h2>Thêm loại sân</h2>

    <!-- Form thêm loại sân mới -->
    <div class="adminedit">
        <form action="{{ route('luu-loai-san') }}" method="POST">
            @csrf
            <div class="adminedit-form-group">
                <label for="name">Tên loại sân:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Lưu thông tin</button>
            </div>
        </form>
    </div>
@endsection
