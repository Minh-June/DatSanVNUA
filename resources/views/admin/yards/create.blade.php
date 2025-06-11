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
<<<<<<< HEAD
    @if(session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif
    
=======
    @if ($errors->any())
        <script>
            alert("{{ $errors->first() }}");
        </script>
    @endif

>>>>>>> 80d6e7c (Cập nhật giao diện)
    <h3>Thêm sân mới</h3>

    <!-- Form thêm sân mới -->
    <div class="adminedit">
        <form action="{{ route('luu-san') }}" method="POST">
            @csrf
            <label for="type_id">Thể loại sân:</label>
            <select id="type_id" name="type_id" required>
                <option value="">Chọn loại sân</option>
                @foreach($types as $type)
                    <option value="{{ $type->type_id }}">{{ $type->name }}</option>
                @endforeach
            </select>
            <br>
            <label for="name">Tên sân:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <button class="update-btn" type="submit">Lưu thông tin sân</button>
        </form>
    </div>
@endsection
