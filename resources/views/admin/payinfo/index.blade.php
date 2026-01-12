@extends('layouts.admin')

@section('title', 'Thông tin thanh toán')

@section('content')  
@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

@if ($errors->any())
    <script>alert("{{ $errors->first() }}");</script>
@endif

<h2>Thông tin thanh toán</h2>

<div class="adminedit">
    <form method="POST" action="{{ route('cap-nhat-thong-tin-thanh-toan') }}" enctype="multipart/form-data">
        @csrf

        <div class="adminedit-form-group">
            <label for="acc_name">Tên tài khoản:</label>
            <input type="text" name="acc_name" value="{{ old('acc_name', $user->acc_name ?? '') }}" required>
        </div>

        <div class="adminedit-form-group">
            <label for="acc_number">Số tài khoản:</label>
            <input type="text" name="acc_number" value="{{ old('acc_number', $user->acc_number ?? '') }}" required>
        </div>

        <div class="adminedit-form-group">
            <label for="acc_type">Ngân hàng:</label>
            <input type="text" name="acc_type" value="{{ old('acc_type', $user->acc_type ?? '') }}" placeholder="VD: Techcombank, Vietinbank..." required>
        </div>

        <div class="adminedit-form-group">
            <p>Mã QR hiện tại:</p>
            @if(!empty($user->qr_code))
                <img src="{{ asset('storage/' . $user->qr_code) }}" alt="QR code" style="max-width: 200px; border-radius: 8px;">
            @else
                <p style="margin:0 0 20px 90px;">Hiện chưa có</p>
            @endif
        </div>

        <div class="adminedit-form-group">
            <label for="qr_code">Mã QR:</label>
            <input type="file" name="qr_code" accept="image/*">
            @if(!empty($user->qr_code))
            @endif
        </div>

        <div class="adminedit-button">
            <button class="update-btn" type="submit">Cập nhật thông tin</button>
        </div>
    </form>
</div>

@endsection
