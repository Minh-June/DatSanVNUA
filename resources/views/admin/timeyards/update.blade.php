@extends('layouts.admin')

@section('title', 'Cập nhật khung giờ')

@section('content')

@if(session('success'))
<script>alert("{{ session('success') }}");</script>
@endif

@if ($errors->any())
<script>alert("{{ $errors->first() }}");</script>
@endif

<h2>Cập nhật khung giờ cho thuê</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ $yard_id ? route('quan-ly-thoi-gian-san', ['yard_id' => $yard_id]) : route('quan-ly-san') }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="adminedit">
    <form action="{{ route('update.time', ['time_id' => $time->time_id]) }}" method="POST">
        @csrf

        <input type="hidden" name="yard_id" value="{{ $time->yard_id }}">

        {{-- Tên sân --}}
        <div class="adminedit-form-group">
            <label>Sân:</label>
            <input type="text" value="{{ $yards->firstWhere('yard_id', $time->yard_id)?->name }}" disabled>
        </div>

        {{-- Giờ bắt đầu --}}
        <div class="adminedit-form-group">
            <label for="start">Bắt đầu:</label>
            <input type="time"
                id="start"
                name="start"
                value="{{ old('start', \Carbon\Carbon::parse($time->start)->format('H:i')) }}"
                required step="60">
        </div>

        {{-- Giờ kết thúc --}}
        <div class="adminedit-form-group">
            <label for="end">Kết thúc:</label>
            <input type="time"
                id="end"
                name="end"
                value="{{ old('end', \Carbon\Carbon::parse($time->end)->format('H:i')) }}"
                required step="60">
        </div>

        {{-- Giá ngày thường --}}
        <div class="adminedit-form-group">
            <label for="price_weekday">Giá T2-T6 (đ):</label>
            <input type="number"
                id="price_weekday"
                name="price_weekday"
                value="{{ old('price_weekday', $time->price_weekday) }}"
                step="1000" min="0">
        </div>

        {{-- Giá cuối tuần --}}
        <div class="adminedit-form-group">
            <label for="price_weekend">Giá T7-CN (đ):</label>
            <input type="number"
                id="price_weekend"
                name="price_weekend"
                value="{{ old('price_weekend', $time->price_weekend) }}"
                step="1000" min="0">
        </div>

        <div class="adminedit-button">
            <button class="update-btn" type="submit">Cập nhật thông tin</button>
        </div>
    </form>
</div>
@endsection
