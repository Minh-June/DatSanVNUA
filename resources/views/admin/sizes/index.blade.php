@extends('layouts.admin')

@section('title', 'Danh sách size sản phẩm')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif
@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

<h2>Quản lý size sản phẩm</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('cap-nhat-san-pham', $product_id) }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="admin-add-btn">
        @if(auth()->user()->role != 3)
            <a class="update-btn" href="{{ route('them-size', $product_id) }}">Thêm size mới</a>
        @endif
    </div>
</div>

<table id='ListCustomers'>
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên size</th>
            <th>Giá tiền (đ)</th>
            <th>Số lượng</th>
            @if(auth()->user()->role != 3)
                <th colspan="2">Tuỳ chọn</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($sizes as $index => $size)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $size->name }}</td>
                <td>{{ number_format($size->price, 0, '', '.') }}đ</td>
                <td>{{ $size->quantity }}</td>

                @if(auth()->user()->role != 3)
                    <td>
                        <form action="{{ route('cap-nhat-size', [$product_id, $size->product_size_id]) }}" method="GET">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>

                    <td>
                        <form action="{{ route('xoa-size', [$product_id, $size->product_size_id]) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa size này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ auth()->user()->role != 3 ? 6 : 4 }}" style="text-align:center;">Chưa có size nào</td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection
