@extends('layouts.admin')

@section('title', 'Sửa Thời gian thuê sân')

@section('content')
    <div class="admin-section">
        <h3>Sửa Thời gian thuê sân</h3>

        <!-- Form chỉnh sửa thông tin sân -->
        <div class="adminedit">
            <form method="POST" action="{{ route('cap-nhat-thoi-gian-san', $time_slot['time_slot_id']) }}">
                @csrf
                <input type="hidden" name="time_slot_id" value="{{ $time_slot['time_slot_id'] }}">
                <div class="form-group">
                    <label for="new_time_slot">Khung giờ mới:</label>
                    <input type="text" name="new_time_slot" id="new_time_slot" value="{{ old('new_time_slot', $time_slot['time_slot']) }}" required><br>
                </div>
                <div class="form-group">
                    <label for="price">Giá tiền:</label>
                    <input type="text" id="price" name="price" value="{{ old('price', $time_slot['price']) }}" required><br>
                </div>
                <button class="update-btn" type="submit" name="update_time_slot">Cập nhật thời gian</button>
            </form>                                                     
        </div>
    </div>
@endsection
