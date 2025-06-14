@extends('layouts.admin')

@section('title', 'Thêm khung giờ sân')

@section('content')
    <!-- Hiển thị thông báo -->
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    @if ($errors->has('time'))
        <script>alert("{{ $errors->first('time') }}");</script>
    @endif

    <h2>Thêm khung giờ cho thuê</h2>

    <!-- Form thêm khung giờ -->
    <div class="adminedit">
        <form action="{{ route('luu-thoi-gian-san') }}" method="POST">
            @csrf

            <input type="hidden" name="yard_id" value="{{ $yard_id }}">
            <div class="adminedit-form-group">
                <label>Sân:</label>
                <input type="text" value="{{ $yards->firstWhere('yard_id', $yard_id)?->name }}" disabled>
            </div>

            <div class="adminedit-form-group">
                <label for="time">Khung giờ:</label>
                <input
                    type="text"
                    id="time"
                    name="time"
                    required
                    pattern="\d{2}:\d{2}\s*-\s*\d{2}:\d{2}"
                    title="Định dạng phải là HH:MM - HH:MM (VD: 06:00 - 07:30)"
                    placeholder="Ví dụ: 06:00 - 07:30"
                >
            </div>

            <div class="adminedit-form-group">
                <label for="price">Giá tiền (đ):</label>
                <input type="number" id="price" name="price" required step="1000">
            </div>

            <div class="adminedit-form-group">
                <label for="date">Ngày áp dụng:</label>
                <input type="date" id="date" name="date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}">
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Lưu khung giờ</button>
            </div>
        </form>
    </div>
@endsection
