@extends('layouts.admin')

@section('title', 'Quản lý hình ảnh sân')

@section('content')
@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif
@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

@if($selectedYard)
    <h2>{{ $selectedYard->type->name ?? 'Loại sân không xác định' }} - {{ $selectedYard->name ?? 'Không xác định' }}</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <a class="update-btn" href="{{ route('quan-ly-san') }}">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        @if($canManage)
            <div class="admin-add-btn">
                <a class="update-btn" href="{{ route('them-hinh-anh-san', ['yard_id' => $selectedYard->yard_id]) }}">
                    Thêm hình ảnh
                </a>
            </div>
        @endif
    </div>

    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Hình ảnh</th>
                @if($canManage)
                    <th colspan="2">Tùy chọn</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($selectedYard->images as $index => $image)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $image->image) }}" 
                             alt="Hình ảnh" 
                             class="football-img"
                             onclick="showImage(this.src)">
                    </td>

                    @if($canManage)
                        <td>
                            <form action="{{ route('cap-nhat-hinh-anh-san', ['image_id' => $image->image_id]) }}" method="GET">
                                <button type="submit" class="update-btn">Sửa</button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('xoa-hinh-anh-san', ['image_id' => $image->image_id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hình ảnh này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
