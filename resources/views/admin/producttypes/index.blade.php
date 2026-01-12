@extends('layouts.admin')

@section('title', 'Quản lý loại sản phẩm')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif
@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

<h2>Quản lý loại sản phẩm</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-cua-hang') }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="admin-add-btn">
        @if(auth()->user()->role != 3)
            <a class="update-btn" href="{{ route('them-loai-san-pham', $store->store_id) }}">Thêm loại sản phẩm</a>
        @endif
    </div>
</div>

<table id='ListCustomers'>
    <thead>
        <tr>
            <th>STT</th>
            <th>Loại sản phẩm</th>
            @if(auth()->user()->role != 3)
                <th colspan="2">Tùy chọn</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($types as $index => $type)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $type->name }}</td>

                @if(auth()->user()->role != 3)
                    <td>
                        <form action="{{ route('cap-nhat-loai-san-pham', $type->product_type_id) }}" method="GET">
                            @csrf
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>

                    <td>
                        <form action="{{ route('xoa-loai-san-pham', $type->product_type_id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa loại sản phẩm này ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ auth()->user()->role != 3 ? 4 : 2 }}" style="text-align:center;">
                    Hiện cửa hàng chưa có loại sản phẩm nào
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
