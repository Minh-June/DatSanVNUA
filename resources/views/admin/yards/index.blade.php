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
    
    <h2>Danh sách sân thể thao</h2>

    <!-- Form tìm kiếm loại sân và thêm sân mới -->
    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="{{ route('quan-ly-san') }}">
                <select id="type_id" name="type_id">
                    <option value="">Chọn loại sân</option>
                    @foreach($types as $type)
                        <option value="{{ $type->type_id }}" 
                            {{ request('type_id') == $type->type_id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
                <button class="update-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a class="update-btn" href="{{ route('them-san') }}">Thêm sân mới</a>
        </div>
    </div>

    <!-- Hiển thị bảng dữ liệu -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Loại sân</th>
                <th>Tên sân</th>
                <th colspan="2">Thông tin</th>
                <th colspan="3">Tuỳ chọn</th>
            </tr>
        </thead>
        <tbody>
            @php
                $index = 0;
                // Nhóm sân theo loại sân (type name)
                $yardsGrouped = $yardsGrouped = $yards->groupBy(fn($yard) => $yard->type->name ?? 'Không tồn tại');
            @endphp

            @foreach ($yardsGrouped as $typeName => $yardsOfType)
                @php
                    $count = $yardsOfType->count();
                @endphp
                @foreach ($yardsOfType as $key => $yard)
                    <tr>
                        <td>{{ ++$index }}</td>
                        {{-- Chỉ hiển thị cột Loại sân 1 lần với rowspan bằng số sân cùng loại --}}
                        @if ($key == 0)
                            <td class="left-align" rowspan="{{ $count }}">{{ $typeName }}</td>
                        @endif
                        <td class="left-align">{{ $yard->name }}</td>
                        <td>
                            <a href="{{ route('quan-ly-thoi-gian-san', ['yard_id' => $yard->yard_id, 'type_id' => request('type_id')]) }}">
                                Thời gian
                            </a><br>
                        </td>
                        <td>
                            <a href="{{ route('quan-ly-hinh-anh-san', ['yard_id' => $yard->yard_id, 'type_id' => request('type_id')]) }}">
                                Hình ảnh
                            </a>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('cap-nhat-trang-thai-san') }}">
                                @csrf
                                <input type="hidden" name="yard_id" value="{{ $yard->yard_id }}">
                                <select name="status">
                                    <option value="0" {{ $yard->status == 0 ? 'selected' : '' }}>Đang hiện</option>
                                    <option value="1" {{ $yard->status == 1 ? 'selected' : '' }}>Đã ẩn</option>
                                </select><br>
                                <button type="submit" class="update-btn">Cập nhật</button>
                            </form>
                        </td>
                        <td>
                            <form method="GET" action="{{ route('cap-nhat-san', ['yard_id' => $yard->yard_id]) }}">
                                <button type="submit" class="update-btn">Sửa</button>
                            </form>
                        </td>                                      
                        <td>
                            <form method="POST" action="{{ route('xoa-san', ['yard_id' => $yard->yard_id, 'type_id' => request('type_id')]) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xoá sân này không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>                                                                           
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@endsection
