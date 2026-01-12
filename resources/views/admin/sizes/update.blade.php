@extends('layouts.admin')

@section('title', 'Sửa thông tin size')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

@if ($errors->any())
    <script>alert("{{ $errors->first() }}");</script>
@endif

<h2>Sửa thông tin size</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-size', $product_id) }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="adminedit">
    <form action="{{ route('update-size', [$product_id, $size->product_size_id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="adminedit-form-group">
            <label for="name">Tên size:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $size->name) }}" required>
        </div>

        <div class="adminedit-form-group">
            <label for="price">Giá tiền (đ):</label>
            <input type="number" id="price" name="price" value="{{ old('price', $size->price) }}" min="0" required>
        </div>

        <div class="adminedit-form-group">
            <label for="quantity">Số lượng:</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', $size->quantity) }}" min="0" required>
        </div>

        <div class="adminedit-button">
            <button class="update-btn" type="submit">Cập nhật thông tin</button>
        </div>
    </form>
</div>

@endsection
