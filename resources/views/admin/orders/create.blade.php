@extends('layouts.admin')

@section('title', 'Thêm khách hàng mới')

@section('content')
    <h3>Thêm khách hàng mới</h3>

    <div class="adminedit">
        <form method="post" action="{{ route('store.order') }}">
            @csrf
            <div class="admin-time">
                <label for='san_id'>Chọn sân:</label>
                <select class="admin-time-select" name='san_id' required>
                    <option value='' selected disabled>Chọn sân</option>
                    @foreach ($sans as $san)
                        <option value='{{ $san->san_id }}'>
                            {{ $san->tensan }} - {{ $san->sosan }}
                        </option>
                    @endforeach
                </select><br>
            </div>

            <label for='name'>Họ và tên:</label>
            <input type='text' name='name' required><br>
            <label for='phone'>Số điện thoại:</label>
            <input type='text' name='phone' required><br>
            <label for='date'>Ngày:</label>
            <input type='date' name='date' required><br>
            <label for='time'>Thời gian:</label>
            <input type='text' name='time' required><br>
            <label for='price'>Thành tiền:</label>
            <input type='text' name='price' required><br>
            <label for='notes'>Ghi chú:</label><br><br>
            <textarea name='notes' rows='4' cols='50'></textarea><br>
            <input type='submit' class="update-btn" value='Thêm khách hàng mới'>
        </form>
    </div>
@endsection
