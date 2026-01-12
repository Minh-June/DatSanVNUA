@extends('layouts.admin')

@section('title', 'Thêm khung giờ sân')

@section('content')
@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

@if ($errors->any())
    <script>alert("{{ $errors->first() }}");</script>
@endif

<h2>Thêm khung giờ cho sân</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ $yard_id ? route('quan-ly-thoi-gian-san', ['yard_id' => $yard_id]) : route('quan-ly-san') }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="adminedit">
    <form action="{{ route('luu-thoi-gian-san') }}" method="POST">
        @csrf
        <input type="hidden" name="yard_id" value="{{ $yard_id }}">

        <div class="adminedit-form-group">
            <label>Sân:</label>
            <input type="text" value="{{ $yards->firstWhere('yard_id', $yard_id)?->name }}" disabled>
        </div>

        <div class="adminedit-form-group">
            <label for="start">Bắt đầu:</label>
            <input type="time" id="start" name="start" value="{{ old('start') }}" required step="60">
        </div>

        <div class="adminedit-form-group">
            <label for="end">Kết thúc:</label>
            <input type="time" id="end" name="end" value="{{ old('end') }}" required step="60">
        </div>

        <div class="adminedit-form-group">
            <label for="price_weekday">Giá T2-T6 (đ):</label>
            <input type="number" id="price_weekday" name="price_weekday" value="{{ old('price_weekday') }}" step="1000" min="0">
        </div>

        <div class="adminedit-form-group">
            <label for="price_weekend">Giá T7-CN (đ):</label>
            <input type="number" id="price_weekend" name="price_weekend" value="{{ old('price_weekend') }}" step="1000" min="0">
        </div>

        <div class="adminedit-button">
            <button class="update-btn" type="submit">Lưu khung giờ</button>
        </div>
    </form>
</div>
@endsection
