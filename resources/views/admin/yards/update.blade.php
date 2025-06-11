@extends('layouts.admin')

@section('title', 'Sửa thông tin sân')

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
=======
    @if ($errors->any())
        <script>
            alert("{{ $errors->first() }}");
>>>>>>> 80d6e7c (Cập nhật giao diện)
        </script>
    @endif

    <h3>Sửa thông tin sân</h3>

    <!-- Form chỉnh sửa thông tin sân -->
    <div class="adminedit">
        <form method="POST" action="{{ route('update.yard', $yard->yard_id) }}">
            @csrf
            <input type="hidden" name="_method" value="POST">

            <label for="type_id">Thể loại sân:</label>
            <select id="type_id" name="type_id" required>
                @foreach($types as $type)
                    <option value="{{ $type->type_id }}" {{ $yard->type_id == $type->type_id ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
            <br>

            <label for="name">Tên sân:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $yard->name) }}" required>
            <br>

            <button class="update-btn" type="submit">Cập nhật thông tin sân</button>
        </form>
    </div>
@endsection
