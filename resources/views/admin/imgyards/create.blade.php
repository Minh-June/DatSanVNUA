@extends('layouts.admin')

@section('title', 'Thêm hình ảnh sân thể thao')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <h2>Thêm hình ảnh sân thể thao</h2>

    <div class="adminedit">
        <form action="{{ route('luu-hinh-anh-san') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="adminedit-form-group">
                <label for="yard_id">Sân thể thao:</label>
                <input type="text" class="admin-time-select" value="{{ $selectedYard->name }}" disabled>
                <input type="hidden" name="yard_id" value="{{ $selectedYard->yard_id }}">
            </div>

            <div class="adminedit-form-group">
                <label for="image">Chọn hình ảnh:</label>
                <input type="file" name="image" id="image" required>
            </div>
            <div class="adminedit-button">
                <button type="submit" class="update-btn">Thêm hình ảnh</button>
            </div>
        </form>
    </div>
@endsection
