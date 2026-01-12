@extends('layouts.admin')

@section('title', 'Thêm loại sản phẩm')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif
@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

<h2>Thêm loại sản phẩm</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-loai-san-pham', $store->store_id) }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="admin-add-btn"></div>
</div>

<div class="adminedit">
    <form method="POST" action="{{ route('luu-loai-san-pham', $store->store_id) }}">
        @csrf
        <div class="adminedit-form-group">
            <label>Loại sản phẩm:</label>
            <input type="text" name="name" placeholder="Nhập tên loại sản phẩm..." required>
        </div>

        <div class="adminedit-button">
            <button type="submit" class="update-btn">Lưu thông tin</button>
        </div>
    </form>
</div>

@endsection
