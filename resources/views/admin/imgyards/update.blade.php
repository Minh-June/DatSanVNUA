@extends('layouts.admin')

@section('title', 'Cập nhật hình ảnh sân')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error') || $errors->any())
        <script>
            alert(`{{ session('error') ? session('error') . '\n' : '' }}{!! implode('\n', $errors->all()) !!}`);
        </script>
    @endif

    <h2>Cập nhật hình ảnh</h2>

    <form method="POST" action="{{ route('cap-nhat-hinh-anh-san', ['image_id' => $image->image_id]) }}" enctype="multipart/form-data">
        @csrf
        <div class="pay-information">
            <div class="admin-img">
                <img src="{{ asset('storage/' . $image->image) }}" 
                    alt="Hình ảnh" 
                    class="football-img"
                    onclick="showImage(this.src)">
            </div>
        </div>

        <div class="pay-information">
            <div class="admin-img">
                <h2>{{ $image->yard->name }}</h2>

                <h3 for="image">Chọn hình ảnh mới:</h3><br>
                <input type="file" name="image" id="image"><br>

                <button type="submit" class="update-btn">Cập nhật</button>
            </div>
        </div>
    </form>                        
@endsection
