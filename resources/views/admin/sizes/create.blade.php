@extends('layouts.admin')

@section('title', 'Thêm size')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

@if ($errors->any())
    <script>alert("{{ $errors->first() }}");</script>
@endif

<h2>Thêm size mới</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-size', $product_id) }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="adminedit">
    <form action="{{ route('luu-size', $product_id) }}" method="POST">
        @csrf

        <div class="adminedit-form-group">
            <label for="name">Tên size:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="adminedit-form-group">
            <label for="price">Giá tiền (đ):</label>
            <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" required>
        </div>

        <div class="adminedit-form-group">
            <label for="quantity">Số lượng:</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" min="0" required>
        </div>

        <div class="adminedit-button">
            <button class="update-btn" type="submit">Lưu thông tin</button>
        </div>
    </form>
</div>

@endsection
