@extends('layouts.admin')

@section('title', 'Thêm sân')

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

    <h2>Thêm sân mới</h2>

    <!-- Form thêm sân mới -->
    <div class="adminedit">
        <form action="{{ route('luu-san') }}" method="POST">
            @csrf
            <div class="adminedit-form-group">
                <label for="type_id">Thể loại sân:</label>
                <select id="type_id" name="type_id" required>
                    <option value="">Chọn loại sân</option>
                    @foreach($types as $type)
                        <option value="{{ $type->type_id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="adminedit-form-group">
                <label for="name">Tên sân:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Lưu thông tin</button>
            </div>
        </form>
    </div>
@endsection
