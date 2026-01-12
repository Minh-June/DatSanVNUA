@extends('layouts.admin')

@section('title', 'Thêm cửa hàng mới')

@section('content')
    <h2>Thêm cửa hàng mới</h2>

    <div class="adminedit">
        <form method="POST" action="{{ route('luu-cua-hang') }}">
            @csrf
            <div class="adminedit-form-group">
                <label>Tên cửa hàng:</label>
                <input type="text" name="name" required>
            </div>
            <div class="adminedit-form-group">
                <label>Trạng thái:</label>
                <select name="status">
                    <option value="0">Hoạt động</option>
                    <option value="1">Ẩn</option>
                </select>
            </div>
            <div class="adminedit-button">
                <button type="submit" class="update-btn">Lưu thông tin</button>
            </div>
        </form>
    </div>
@endsection
