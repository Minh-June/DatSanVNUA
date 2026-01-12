@extends('layouts.admin')

@section('title', 'Sửa thông tin sân')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if ($errors->any())
        <script>alert("{{ $errors->first() }}");</script>
    @endif

    <h2>Sửa thông tin sân</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <a class="update-btn" href="{{ route('quan-ly-san') }}">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="admin-add-btn"></div>
    </div>

    <!-- Form chỉnh sửa thông tin sân -->
    <div class="adminedit">
        <form method="POST" action="{{ route('update.yard', $yard->yard_id) }}">
            @csrf

            <div class="adminedit-form-group">
                <label for="type_id">Thể loại sân:</label>
                <select id="type_id" name="type_id" required>
                    @foreach($types as $type)
                        <option value="{{ $type->type_id }}" {{ $yard->type_id == $type->type_id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="adminedit-form-group">
                <label for="name">Tên sân:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $yard->name) }}" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Cập nhật thông tin</button>
            </div>
        </form>
    </div>
@endsection
