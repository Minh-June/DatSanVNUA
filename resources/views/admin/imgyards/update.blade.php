@extends('layouts.admin')

@section('title', 'Cập nhật hình ảnh sân')

@section('content')
    <h3>Cập nhật hình ảnh</h3>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif

    <form method="POST" action="{{ route('cap-nhat-hinh-anh-san', ['image_id' => $image->image_id]) }}" enctype="multipart/form-data">
        @csrf
        <div class="pay-information">
            <div class="admin-img">
                <!-- Hiển thị ảnh hiện tại -->
                <img src="{{ asset('storage/' . $image->image) }}" alt="Hình ảnh" class="admin-image">
            </div>
        </div>

        <div class="pay-information">
            <div class="admin-img">
                <h3>{{ $image->yard->name }}</h3>

                <label for="image">Chọn hình ảnh mới:</label><br><br>
                <input type="file" name="image" id="image"><br>

                @error('image')
                    <div class="error">{{ $message }}</div>
                @enderror

                <button type="submit" class="update-btn">Cập nhật</button>
            </div>
        </div>
    </form>                        
@endsection
