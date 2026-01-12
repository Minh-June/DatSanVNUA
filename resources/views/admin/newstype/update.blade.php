@extends('layouts.admin')

@section('title', 'Cập nhật loại tin tức')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if ($errors->any())
        <script>alert("{{ $errors->first() }}");</script>
    @endif

    <h2>Cập nhật loại tin tức</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <a class="update-btn" href="{{ route('quan-ly-loai-tin-tuc') }}">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="admin-add-btn"></div>
    </div>

    <!-- Form chỉnh sửa loại tin tức -->
    <div class="adminedit">
        <form method="POST" action="{{ route('update.news_type', $type->news_type_id) }}">
            @csrf
            <input type="hidden" name="_method" value="POST">

            <div class="adminedit-form-group">
                <label for="name">Tên loại tin tức:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $type->name) }}" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Cập nhật thông tin</button>
            </div>
        </form>
    </div>
@endsection
