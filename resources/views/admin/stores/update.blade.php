@extends('layouts.admin')

@section('title', 'Cập nhật thông tin cửa hàng')

@section('content')
    <h2>Cập nhật thông tin cửa hàng</h2>

    <div class="adminedit">
        <form method="POST" action="{{ route('update.stores', $store->store_id) }}">
            @csrf
            <div class="adminedit-form-group">
                <label>Tên cửa hàng:</label>
                <input type="text" name="name" value="{{ $store->name }}" required>
            </div>

            <div class="adminedit-button">
                <button type="submit" class="update-btn">Lưu thông tin</button>
            </div>
        </form>
    </div>
@endsection
