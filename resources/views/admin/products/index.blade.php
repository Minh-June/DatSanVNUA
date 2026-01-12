@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm')

@section('content')

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif
@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

<h2>Quản lý sản phẩm</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <form method="GET" action="{{ route('quan-ly-san-pham', $store->store_id) }}">
            <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>

    <div class="admin-add-btn">
        <a class="update-btn" href="{{ route('them-san-pham', $store->store_id) }}">Thêm sản phẩm</a>
    </div>
</div>

<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Loại SP</th>
            <th colspan="2">Sản phẩm</th>
            <th>Giá (đ)</th>
            <th>Tồn kho</th>
            <th>Thông tin</th>
            @if(auth()->user()->role != 3)
                <th colspan="3">Tùy chọn</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="left-align">{{ $product->type->name ?? 'Chưa có loại' }}</td>
                <td>
                    @if($product->images->first() && $product->images->first()->image)
                        <img
                            src="{{ asset('storage/' . $product->images->first()->image) }}"
                            alt="Ảnh sản phẩm"
                            style="width:80px;"
                        >
                    @else
                        Không có ảnh
                    @endif
                </td>
                <td class="left-align">
                    @php
                        $words = explode(' ', $product->name);
                        $chunks = array_chunk($words, 9);
                    @endphp
                    @foreach($chunks as $chunk)
                        {{ implode(' ', $chunk) }}<br>
                    @endforeach
                </td>
                <td>
                    @if($product->product_size_id)
                        Có nhiều size
                    @else
                        {{ $product->price ? number_format($product->price, 0, ',', '.') . 'đ' : 'Chưa có giá' }}
                    @endif
                </td>
                <td>
                    @if($product->product_size_id)
                        {{ $product->sizes->sum('quantity') }}
                    @else
                        {{ $product->quantity ?? 0 }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('cap-nhat-san-pham', $product->product_id) }}">Nội dung</a>
                </td>

                @if(auth()->user()->role != 3)
                    {{-- Cập nhật trạng thái --}}
                    <td>
                        <form method="POST" action="{{ route('cap-nhat-trang-thai-san-pham', $product->product_id) }}">
                            @csrf
                            <select name="status">
                                <option value="0" {{ $product->status == 0 ? 'selected' : '' }}>Đang hiện</option>
                                <option value="1" {{ $product->status == 1 ? 'selected' : '' }}>Đã ẩn</option>
                            </select><br>
                            <button type="submit" class="update-btn">Cập nhật</button>
                        </form>
                    </td>

                    {{-- Xóa --}}
                    <td>
                        <form action="{{ route('xoa-san-pham', $product->product_id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ auth()->user()->role != 3 ? 11 : 6 }}" style="text-align:center;">Hiện cửa hàng chưa có sản phẩm nào</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
