@extends('layouts.admin')

@section('title', 'Sửa thông tin sân')

@section('content')
    <div class="admin-section">
        <h3>Sửa thông tin sân</h3>

        <!-- Form chỉnh sửa thông tin sân -->
        <div class="adminedit">
            <form method="POST" action="{{ route('yards.update', $yard->san_id) }}">
                @csrf
                <input type="hidden" name="_method" value="POST">
                <label for="tensan">Tên sân:</label>
                <input type="text" id="tensan" name="tensan" value="{{ old('tensan', $yard->tensan) }}" required>
                <br>
                <label for="sosan">Số sân:</label>
                <input type="text" id="sosan" name="sosan" value="{{ old('sosan', $yard->sosan) }}" required>
                <br>
                <button class="update-btn" type="submit">Cập nhật thông tin sân</button>
            </form>
        </div>
    </div>
@endsection
