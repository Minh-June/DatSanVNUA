@extends('layouts.admin')

@section('title', 'Cập nhật khung giờ')

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

    <h2>Cập nhật khung giờ cho thuê</h2>

    <div class="adminedit">
        <form action="{{ route('update.time', ['time_id' => $time->time_id]) }}" method="POST">
            @csrf

            <input type="hidden" name="yard_id" value="{{ $time->yard_id }}">
            <div class="adminedit-form-group">
                <label>Sân:</label>
                <input type="text" value="{{ $yards->firstWhere('yard_id', $time->yard_id)?->name }}" disabled>
            </div>

            <div class="adminedit-form-group">
                <label for="time">Khung giờ:</label>
                <input
                    type="text"
                    id="time"
                    name="time"
                    value="{{ old('time', $time->time) }}"
                    required
                    pattern="\d{2}:\d{2}\s*-\s*\d{2}:\d{2}"
                    title="Định dạng phải là HH:MM - HH:MM (VD: 06:00 - 07:30)"
                    placeholder="Ví dụ: 06:00 - 07:30"
                >
            </div>

            <div class="adminedit-form-group">
                <label for="price">Giá tiền (đ):</label>
                <input
                    type="number"
                    id="price"
                    name="price"
                    value="{{ old('price', $time->price) }}"
                    required
                    step="1000"
                    min="0"
                >
            </div>

            <input type="hidden" name="date" value="{{ old('date', $time->date) }}">

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Cập nhật</button>
            </div>
        </form>
    </div>
@endsection
