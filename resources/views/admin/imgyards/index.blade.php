@extends('layouts.admin')

@section('title', 'Quản lý hình ảnh sân')

@section('content')
    <!-- Hiển thị thông báo thành công -->
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <h2>Quản lý hình ảnh - {{ $selectedYard->name }}</h2>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            <a class="update-btn" href="{{ isset($selectedYard) ? route('them-hinh-anh-san', ['yard_id' => $selectedYard->yard_id]) : route('them-hinh-anh-san') }}">
                Thêm hình ảnh
            </a>
        </div>
    </div>

    <!-- Hiển thị bảng hình ảnh khi đã chọn sân -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Hình ảnh</th>
                <th colspan="2">Tùy chọn</th>
            </tr>
        </thead>
        <tbody>
            @foreach($selectedYard->images as $index => $image)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $image->image) }}" 
                            alt="Hình ảnh" 
                            class="football-img"
                            onclick="showImage(this.src)">
                    </td>
                    <td>
                        <form action="{{ route('cap-nhat-hinh-anh-san', ['image_id' => $image->image_id]) }}" method="GET">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('xoa-hinh-anh-san', ['image_id' => $image->image_id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hình ảnh này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>                                
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
