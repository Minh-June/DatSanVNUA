@extends('layouts.admin')

@section('title', 'Danh sách loại tin tức')

@section('content')
    <!-- Thông báo thành công -->
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    <!-- Thông báo lỗi -->
    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <h2>Danh sách loại tin tức</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="{{ route('quan-ly-loai-tin-tuc') }}">
                <select id="type_id" name="type_id">
                    <option value="">Chọn loại tin tức</option>
                    @foreach($allTypes as $type)
                        <option value="{{ $type->news_type_id }}" 
                            {{ request('type_id') == $type->news_type_id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                <button class="update-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a class="update-btn" href="{{ route('them-loai-tin-tuc') }}">Thêm loại tin tức</a>
        </div>
    </div>

    <table id="ListCustomers">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên loại tin tức</th>
                <th colspan="2">Tuỳ chọn</th>
            </tr>
        </thead>
        <tbody>
            @forelse($types as $key => $type)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="left-align">{{ $type->name }}</td>
                    <td>
                        <form method="GET" action="{{ route('cap-nhat-loai-tin-tuc', ['news_type_id' => $type->news_type_id]) }}">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('xoa-loai-tin-tuc', $type->news_type_id) }}"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xoá loại tin tức này không?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;">Chưa có loại tin tức nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
