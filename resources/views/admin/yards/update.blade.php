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
    @if ($errors->any())
        <script>
            alert("{{ $errors->first() }}");
        </script>
    @endif

    <h2>Sửa thông tin sân</h2>

    <!-- Form chỉnh sửa thông tin sân -->
    <div class="adminedit">
        <form method="POST" action="{{ route('update.yard', $yard->yard_id) }}">
            @csrf
            {{-- <input type="hidden" name="_method" value="POST"> --}}

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
