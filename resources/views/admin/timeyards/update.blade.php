@extends('layouts.admin')

@section('title', 'Cập nhật khung giờ sân')

@section('content')
    <!-- Hiển thị thông báo -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <h3>Cập nhật khung giờ</h3>

    <!-- Form cập nhật khung giờ -->
    <div class="adminedit">
        <form action="{{ route('update.time', ['time_id' => $time->time_id]) }}" method="POST">
            @csrf

            <label for="yard_id">Chọn sân:</label>
            <select id="yard_id" name="yard_id" required>
                @foreach($yards as $yard)
                    <option value="{{ $yard->yard_id }}" {{ $yard->yard_id == $time->yard_id ? 'selected' : '' }}>
                        {{ $yard->name }}
                    </option>
                @endforeach
            </select>
            <br>

            <label for="time">Khung giờ:</label>
<<<<<<< HEAD
            <input type="text" id="time" name="time" value="{{ $time->time }}" required>
            <br>
=======
            <input type="text" id="time" name="time" required pattern="\d{2}:\d{2}\s*-\s*\d{2}:\d{2}" title="Định dạng phải là HH:MM - HH:MM">            <br>
>>>>>>> 80d6e7c (Cập nhật giao diện)

            <label for="price">Giá (VNĐ):</label>
            <input type="number" id="price" name="price" value="{{ $time->price }}" required min="0">
            <br>

            <label for="date">Ngày áp dụng:</label>
            <input type="date" id="date" name="date" value="{{ $time->date }}" required>
            <br>

            <button class="update-btn" type="submit">Cập nhật khung giờ</button>
        </form>
    </div>
@endsection
