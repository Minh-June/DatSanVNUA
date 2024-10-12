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

    <h3>Danh sách hình ảnh sân</h3>

    <!-- Hiển thị bảng dữ liệu -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sân</th>
                <th>Số sân</th>
                <th>Hình ảnh</th>
                <th>Cập nhật</th>
                <th>Xóa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sans as $san)
                @if($san->images->isEmpty())
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $san->tensan }}</td>
                        <td>{{ $san->sosan }}</td>
                        <td colspan="3">Chưa có ảnh sân</td>
                    </tr>
                @else
                    @foreach($san->images as $image)
                        <tr>
                            <!-- Sử dụng $loop->parent để lấy chỉ số của sân -->
                            <td>{{ $loop->parent->iteration }}</td>
                            <td>{{ $san->tensan }}</td>
                            <td>{{ $san->sosan }}</td>
                            <td>
                                <img src="{{ asset(Storage::url($image->image)) }}" alt="Hình ảnh" class="admin-image">
                            </td>
                            <td>
                                <form action="{{ route('sua-hinh-anh-san', ['image_id' => $image->image_id]) }}" method="GET">
                                    <button type="submit" class="btn btn-primary">Sửa</button>
                                </form>
                            </td>                            
                            <td>
                                <form action="{{ route('xoa-hinh-anh-san', ['image_id' => $image->image_id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hình ảnh này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                </form>                                
                            </td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
@endsection
