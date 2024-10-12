@extends('layouts.admin')

@section('title', 'Cập nhật thông tin khách đặt sân thể thao')

@section('content')
    <h3>Cập nhật thông tin khách hàng</h3>

    <div class="adminedit">
        <form method="POST" action="{{ route('orders.update', $order->order_id) }}">
            @csrf
            <input type="hidden" name="order_id" value="{{ $order->order_id }}">

            <div class="admin-time">
                <label for='san_id'>Chọn sân:</label>
                <select class="admin-time-select" name='san_id' required>
                    @foreach($sans as $san)
                        <option value='{{ $san->san_id }}' {{ $san->san_id == $order->san_id ? 'selected' : '' }}>
                            {{ $san->tensan . " - " . $san->sosan }}
                        </option>
                    @endforeach
                </select><br>
            </div>                        

            <label for='name'>Họ và tên:</label>
            <input type='text' name='name' value='{{ $order->name }}' required><br>
            <label for='phone'>Số điện thoại:</label>
            <input type='text' name='phone' value='{{ $order->phone }}' required><br>
            <label for='date'>Ngày:</label>
            <input type='date' name='date' value='{{ $order->date }}' required><br>
            <label for='time'>Thời gian:</label>
            <input type='text' name='time' value='{{ $order->time }}' required><br>
            <label for='price'>Thành tiền:</label>
            <input type='text' name='price' value='{{ $order->price }}' required><br>
            <label for='notes'>Ghi chú:</label><br><br>
            <textarea name='notes' rows='4' cols='50'>{{ $order->notes }}</textarea><br>
            <input type='submit' class="update-btn" value='Cập nhật thông tin khách hàng'>
        </form>                          
    </div>
@endsection
