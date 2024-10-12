@extends('layouts.admin')

@section('title', 'Cập nhật hình ảnh sân')

@section('content')
    <h3>Cập nhật hình ảnh sân</h3>

    @if (session('success'))
        <script>
            alert('{{ session('success') }}');
        </script>
    @endif

    <form method="POST" action="{{ route('sua-hinh-anh-san', ['image_id' => $image->image_id]) }}" enctype="multipart/form-data">
        @csrf <!-- Thêm CSRF token nếu cần -->
        <div class="pay-information">
            <div class="admin-img">
                <img src="{{ asset(Storage::url($image->image)) }}" alt="Hình ảnh" class="admin-image">
            </div>
        </div>

        <div class="pay-information">
            <div class="admin-img">
                <h3>{{ $tensan . ' - ' . $sosan }}</h3><br><br>
                <label for="image">Chọn hình ảnh mới:</label><br><br>
                <input class="admin-time-select" type="file" name="image" id="image"><br><br>
                <input type="submit" class="update-btn" value="Cập nhật hình ảnh sân">
            </div>
        </div>
    </form>                        
@endsection
