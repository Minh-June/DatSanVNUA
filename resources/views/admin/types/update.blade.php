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
    @if ($errors->any())
        <script>
            alert("{{ $errors->first() }}");
        </script>
    @endif


    <h2>Sửa thông tin loại sân</h2>

    <!-- Form chỉnh sửa thông tin loại sân -->
    <div class="adminedit">
        <form method="POST" action="{{ route('update.type', $type->type_id) }}">
            @csrf
            <input type="hidden" name="_method" value="POST">
            
            <div class="adminedit-form-group">
                <label for="name">Tên loại sân:</label>
                <input type="text" id="name" name="name" value="{{ old('name', $type->name) }}" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Cập nhật thông tin</button>
        </form>
    </div>
@endsection
