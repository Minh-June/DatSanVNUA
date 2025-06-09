@extends('layouts.admin')

@section('title', 'Quản lý hình ảnh sân')

@section('content')
    <!-- Hiển thị thông báo thành công -->
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

    <h3>
        Quản lý hình ảnh 
        @if(isset($selectedYard))
            - {{ $selectedYard->name }}
        @endif
    </h3>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            <a href="{{ route('them-hinh-anh-san') }}">Thêm hình ảnh sân</a>
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
                        <img src="{{ asset('storage/' . $image->image) }}" alt="Hình ảnh" class="admin-image">
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
                            <button type="submit" class="update-btn">Xóa</button>
                        </form>                                
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
