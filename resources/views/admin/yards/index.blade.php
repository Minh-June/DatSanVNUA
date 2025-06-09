@extends('layouts.admin')

@section('title', 'Danh sách sân')

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
    
    <h3>Danh sách sân thể thao</h3>

    <!-- Form tìm kiếm loại sân và thêm loại sân mới -->
    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="{{ route('quan-ly-san') }}">
                <label for="type_id">Chọn loại sân:</label>
                <select id="type_id" name="type_id">
                    <option value="">Tất cả</option>
                    @foreach($types as $type)
                        <option value="{{ $type->type_id }}" 
                            {{ request('type_id') == $type->type_id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
                <button class="admin-search-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a href="{{ route('them-san') }}">Thêm sân mới</a>
        </div>
    </div>

    <!-- Hiển thị bảng dữ liệu -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sân</th>
                <th colspan="2">Thông tin</th>
                <th colspan="2">Tùy chọn</th>
            </tr>
        </thead>
        <tbody>
            @foreach($yards as $key => $yard)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $yard->name }}</td>
                <td>
                    <a href="{{ route('quan-ly-thoi-gian-san', ['yard_id' => $yard->yard_id]) }}">Thời gian</a><br>
                </td>
                <td>
                    <a href="{{ route('quan-ly-hinh-anh-san', ['yard_id' => $yard->yard_id]) }}">Hình ảnh</a>
                </td>
                    <td>
                        <form method="GET" action="{{ route('cap-nhat-san', ['yard_id' => $yard->yard_id]) }}">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>                                      
                    <td>
                        <form method="POST" action="{{ route('xoa-san', ['yard_id' => $yard->yard_id, 'type_id' => request('type_id')]) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sân này không?')">
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
