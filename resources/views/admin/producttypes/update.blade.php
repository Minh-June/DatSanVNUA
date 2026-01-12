@extends('layouts.admin')

@section('title', 'Cập nhật loại sản phẩm')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif
@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

<h2>Cập nhật loại sản phẩm</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-loai-san-pham', $store->store_id) }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="admin-add-btn"></div>
</div>

<div class="adminedit">
    <form method="POST" action="{{ route('update.loai-san-pham', $type->product_type_id) }}">
        @csrf
        <div class="adminedit-form-group">
            <label>Loại sản phẩm:</label>
            <input type="text" name="name" value="{{ $type->name }}" required>
        </div>
        <div class="adminedit-button">
            <button type="submit" class="update-btn">Cập nhật thông tin</button>
        </div>
    </form>
</div>

@endsection
