@extends('layouts.admin')

@section('title', 'Thêm loại tin tức')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if ($errors->any())
        <script>alert("{{ $errors->first() }}");</script>
    @endif

    <h2>Thêm loại tin tức</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <a class="update-btn" href="{{ route('quan-ly-loai-tin-tuc') }}">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="admin-add-btn"></div>
    </div>

    <!-- Form thêm loại tin tức mới -->
    <div class="adminedit">
        <form action="{{ route('luu-loai-tin-tuc') }}" method="POST">
            @csrf
            <div class="adminedit-form-group">
                <label for="name">Tên loại tin tức:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Lưu thông tin</button>
            </div>
        </form>
    </div>
@endsection
