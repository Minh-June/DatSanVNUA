@extends('layouts.admin')

@section('title', 'Danh sách loại sân')

@section('content')
    <!-- Hiển thị thông báo -->
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

    <h2>Danh sách loại sân thể thao</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="{{ route('quan-ly-loai-san') }}">
                <select id="type_id" name="type_id">
                    <option value="">Chọn loại sân</option>
                    @foreach($allTypes as $type)
                        <option value="{{ $type->type_id }}" {{ request('type_id') == $type->type_id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                <button class="update-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a class="update-btn" href="{{ route('them-loai-san') }}">Thêm loại sân mới</a>
        </div>
    </div>
    
    <!-- Hiển thị bảng dữ liệu -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên loại sân</th>
                <th colspan="2">Tuỳ chọn</th>
            </tr>
        </thead>
        <tbody>
            @foreach($types as $key => $type)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="left-align">{{ $type->name }}</td>
                    <td>
                        <form method="GET" action="{{ route('cap-nhat-loai-san', ['type_id' => $type->type_id]) }}">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>                                      
                    <td>
                        <form method="POST" action="{{ route('xoa-loai-san', $type->type_id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xoá loại sân này không?')">
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
