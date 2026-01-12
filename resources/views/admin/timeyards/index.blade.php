@extends('layouts.admin')

@section('title', 'Quản lý khung giờ sân')

@section('content')
@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

<h2>{{ $yard->type->name ?? 'Loại sân không xác định' }} - {{ $yard->name ?? 'Không xác định' }}</h2>

@php
    $user = auth()->user();
    $owner = $yard->user ?? null;
    $canManage = false;

    if($owner) {
        if($user->role == 0 && $owner->role == 0 && $owner->user_id == $user->user_id) {
            // Admin xem sân do chính họ quản lý
            $canManage = true;
        } elseif($user->role == 2 && $owner->user_id == $user->user_id) {
            // Chủ sân xem sân của mình
            $canManage = true;
        }
        // Nhân viên role=3 luôn ẩn cột
    }
@endphp

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-san') }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if($canManage)
    <div class="admin-add-btn">
        <a class="update-btn" href="{{ route('them-thoi-gian-san', ['yard_id' => $yard->yard_id]) }}">
            Thêm khung giờ cho thuê
        </a>
    </div>
    @endif
</div>

<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Khung giờ</th>
            <th>Giá T2-T6 (đ)</th>
            <th>Giá T7-CN (đ)</th>
            @if($canManage)
                <th colspan="3">Tùy chọn</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @forelse($times as $index => $time)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($time->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($time->end)->format('H:i') }}</td>
                <td>
                    {{ ($time->price_weekday && $time->price_weekday > 0) 
                        ? number_format($time->price_weekday, 0, ',', '.') . 'đ' 
                        : 'Không cho thuê' }}
                </td>
                <td>
                    {{ ($time->price_weekend && $time->price_weekend > 0) 
                        ? number_format($time->price_weekend, 0, ',', '.') . 'đ' 
                        : 'Không cho thuê' }}
                </td>

                @if($canManage)
                    <td>
                        <form method="POST" action="{{ route('cap-nhat-trang-thai-thoi-gian-dat-san', ['_id' => $time->time_id]) }}">
                            @csrf
                            <select name="status">
                                <option value="0" {{ $time->status == 0 ? 'selected' : '' }}>Hiển thị</option>
                                <option value="1" {{ $time->status == 1 ? 'selected' : '' }}>Ẩn</option>
                            </select><br>
                            <button type="submit" class="update-btn">Cập nhật</button>
                        </form>
                    </td>
                    <td>
                        <form method="GET" action="{{ route('cap-nhat-thoi-gian-san', ['time_id' => $time->time_id]) }}">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('xoa-thoi-gian-san', ['time_id' => $time->time_id]) }}"
                              onsubmit="return confirm('Bạn có chắc chắn muốn xoá khung giờ này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ $canManage ? 7 : 4 }}" style="text-align:center;">
                    Chưa có khung giờ nào.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection
