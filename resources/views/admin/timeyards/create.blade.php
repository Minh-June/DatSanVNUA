@extends('layouts.admin')

@section('title', 'Thêm khung giờ sân')

@section('content')
    <!-- Hiển thị thông báo -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <!-- Hiển thị thông báo lỗi -->
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <h3>Thêm khung giờ cho thuê</h3>

    <!-- Form thêm khung giờ -->
    <div class="adminedit">
        <form action="{{ route('luu-thoi-gian-san') }}" method="POST">
            @csrf

            <label for="yard_id">Chọn sân:</label>
            <select id="yard_id" name="yard_id" required>
                <option value="">Chọn sân</option>
                @foreach($yards as $yard)
                    <option value="{{ $yard->yard_id }}">{{ $yard->name }}</option>
                @endforeach
            </select>
            <br>

            <label for="time">Khung giờ:</label>
<<<<<<< HEAD
            <input type="text" id="time" name="time" required>
            <br>

            <label for="price">Giá (VNĐ):</label>
            <input type="number" id="price" name="price" required min="0">
=======
            <input type="text" id="time" name="time" required pattern="\d{2}:\d{2}\s*-\s*\d{2}:\d{2}" title="Định dạng phải là HH:MM - HH:MM">
            <br>

            <label for="price">Giá (VNĐ):</label>
            <input type="number" id="price" name="price" required min="0" step="1000">
>>>>>>> 80d6e7c (Cập nhật giao diện)
            <br>

            <label for="date">Ngày áp dụng:</label>
            <input type="date" id="date" name="date" required>
            <br>

            <button class="update-btn" type="submit">Lưu khung giờ</button>
        </form>
    </div>
@endsection
