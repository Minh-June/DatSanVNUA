@extends('layouts.admin')

@section('title', 'Quản lý cửa hàng thể thao')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif
@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

@php
    $user = auth()->user();
@endphp

<h2>Quản lý cửa hàng</h2>

<div class="admin-top-bar">
    <div class="admin-search"></div>
    
    <div class="admin-add-btn">
        @if($user->role == 2)
            @php
                // Kiểm tra user hiện tại đã có cửa hàng chưa
                $hasStore = $stores->where('user_id', $user->user_id)->isNotEmpty();
            @endphp

            @if(!$hasStore)
                <a class="update-btn" href="{{ route('them-cua-hang') }}">Thêm cửa hàng mới</a>
            @endif
        @endif
    </div>
</div>

<table id='ListCustomers'>
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên cửa hàng</th>
            <th>Chủ sở hữu</th>
            <th>Số điện thoại</th>
            <th colspan="2">Thông tin</th>
            {{-- Nếu role = 3 thì ẩn toàn bộ cột tùy chọn --}}
            @if($user->role != 3)
                <th colspan="3">Tùy chọn</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @if($stores->isEmpty())
            <tr>
                <td colspan="{{ $user->role != 3 ? 7 : 4 }}" style="text-align:center;">Hiện chưa có cửa hàng nào</td>
            </tr>
        @else
            @foreach($stores as $index => $store)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $store->name }}</td>
                    <td>{{ $store->user->fullname ?? '-' }}</td>
                    <td>{{ $store->user->phonenb ?? '-' }}</td>
                    <td>
                        <a href="{{ route('quan-ly-loai-san-pham', [$store->store_id]) }}">Loại sản phẩm</a><br>
                    </td>
                    <td>
                        <a href="{{ route('quan-ly-san-pham', [$store->store_id]) }}">Sản phẩm</a>
                    </td>

                    {{-- Chỉ hiển thị cột tùy chọn nếu không phải role 3 --}}
                    @if($user->role != 3)
                        <td>
                            <form action="{{ route('cap-nhat-trang-thai-cua-hang', $store->store_id) }}" method="POST" style="display:inline;">
                                @csrf
                                <select name="status">
                                    <option value="0" {{ $store->status == 0 ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="1" {{ $store->status == 1 ? 'selected' : '' }}>Đã Ẩn</option>
                                </select><br>
                                <button type="submit" class="update-btn">Cập nhật</button>
                            </form>
                        </td>
                        @if(in_array($user->role, [0,2]))
                            <td>
                                <form method="GET" action="{{ route('cap-nhat-thong-tin-cua-hang', $store->store_id) }}">
                                    <button type="submit" class="update-btn">Sửa</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('xoa-cua-hang', $store->store_id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xoá cửa hàng này không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-btn">Xóa</button>
                                </form>
                            </td>
                        @endif
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

@php
    $user = auth()->user();
@endphp

@if($user->role == 2)
    <h2>Danh sách nhân viên</h2>

    <div class="admin-top-bar">
        <div class="admin-search"></div>
        <div class="admin-add-btn"></div>
    </div>

    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên nhân viên</th>
                <th>Ngày sinh</th>
                <th>Số điện thoại</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $index => $emp)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $emp->fullname }}</td>
                    <td>{{ \Carbon\Carbon::parse($emp->birthdate)->format('d/m/Y') }}</td>
                    <td>{{ $emp->phonenb }}</td>
                    <td>{{ $emp->email }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Cửa hàng chưa có nhân viên nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endif

@endsection
