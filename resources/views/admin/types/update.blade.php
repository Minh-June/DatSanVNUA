@extends('layouts.admin') 

@section('title', 'Sửa thông tin loại sân')

@section('content')
    <!-- Hiển thị thông báo -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <!-- Hiển thị thông báo lỗi -->
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif

    <h3>Sửa thông tin loại sân</h3>

    <!-- Form chỉnh sửa thông tin loại sân -->
    <div class="adminedit">
        <form method="POST" action="{{ route('update.type', $type->type_id) }}">
            @csrf
            <input type="hidden" name="_method" value="POST">
            <label for="name">Tên loại sân:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $type->name) }}" required>
            <br>
            <button class="update-btn" type="submit">Cập nhật thông tin loại sân</button>
        </form>
    </div>
@endsection
