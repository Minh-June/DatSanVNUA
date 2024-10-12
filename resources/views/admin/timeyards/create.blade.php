@extends('layouts.admin')

@section('title', 'Thêm thời gian thuê sân')

@section('content')
    <div class="admin-section">
        <h3>Thêm thời gian thuê sân</h3>

        <!-- Begin: Form thêm khung giờ -->
        <div class="adminedit">
            <form method="POST" action="{{ route('store.time_slot') }}">
                @csrf
                <div class="form-group">
                    <label for="san_id">Chọn sân:</label>
                    <select class="admin-time-select" name="san_id" id="san_id">
                        @foreach($san_list as $san)
                            <option value="{{ $san->san_id }}">{{ $san->tensan }} - {{ $san->sosan }}</option>
                        @endforeach
                    </select>
                </div><br>
                
                <div class="form-group">
                    <label for="time_slot">Khung giờ:</label>
                    <input type="text" id="time_slot" name="time_slot" required><br>
                    <label for="price">Giá tiền:</label>
                    <input type="text" id="price" name="price" required>
                </div>
                <button class="admin-time-btn" type="submit" name="add_time_slot">Thêm khung giờ</button>
            </form>
        </div>
        <!-- End: Form thêm khung giờ -->
    </div>
@endsection
