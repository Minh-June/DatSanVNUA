@extends('layouts.admin')

@section('title', 'Thêm sân')

@section('content')
    <div class="admin-section">
        <h3>Thêm sân</h3>

        <!-- Form thêm sân mới -->
        <div class="adminedit">
            <form action="{{ route('them-san') }}" method="POST">
                @csrf <!-- Thêm CSRF token -->
                <label for="tensan">Tên sân mới:</label>
                <input type="text" id="tensan" name="tensan" required>
                <br>
                <label for="sosan">Số sân:</label>
                <input type="text" id="sosan" name="sosan" required>
                <br>
                <button class="update-btn" type="submit">Lưu thông tin sân</button>
            </form>
        </div>
    </div>
@endsection
