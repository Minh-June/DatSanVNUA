@extends('layouts.admin')

@section('title', 'Danh sách sân')

@section('content')
    <!-- Hiển thị thông báo -->
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

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

        @if(auth()->user()->role != 3 && auth()->user()->role != 2) {{-- Ẩn với role 3 và 2 --}}
            <div class="admin-add-btn">
                <a class="update-btn" href="{{ route('them-san') }}">Thêm sân mới</a>
            </div>
        @endif
    </div>

    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Loại sân</th>
                <th>Tên sân</th>
                @if(auth()->user() && auth()->user()->role == 0)
                    <th colspan="3">Thông tin</th>
                @else
                    <th colspan="2">Thông tin</th>
                @endif
                @if(auth()->user()->role != 3)
                    <th colspan="3">Tuỳ chọn</th> {{-- Chỉ hiển thị nếu không phải nhân viên --}}
                @endif
            </tr>
        </thead>
        <tbody>
            @php
                $index = 0;
                $yardsGrouped = $yards->groupBy(fn($yard) => $yard->type->name ?? 'Không tồn tại');
            @endphp

            @foreach ($yardsGrouped as $typeName => $yardsOfType)
                @php
                    $count = $yardsOfType->count();
                @endphp
                @foreach ($yardsOfType as $key => $yard)
                    @php
                        $isBlocked = $yard->user && $yard->user->role == 2; // sân đã phân cho role=2
                    @endphp
                    <tr>
                        <td>{{ ++$index }}</td>
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
                        @if(auth()->user()->role == 0)
                            <td>
                                <a href="{{ route('thong-tin-don-vi-thau') }}?yard_id={{ $yard->yard_id }}&user_id={{ $yard->user->user_id ?? \App\Models\User::where('role', 0)->first()->user_id }}">
                                    Đơn vị thầu
                                </a>
                            </td>
                        @endif
                        
                        @php
                            $hideForUser = in_array(auth()->user()->role, [2,3]);
                            $isAdminBlocked = auth()->user()->role == 0 && $yard->user && $yard->user->role == 2;
                        @endphp

                        {{-- Cập nhật / Sửa / Xóa --}}
                        @if(auth()->user()->role != 3)
                            @if($isAdminBlocked)
                                <td colspan="3" style="color:var(--primary-color);">
                                    Đang khai thác (bởi đối tác)
                                </td>
                            @else
                                {{-- Cập nhật trạng thái --}}
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

                                {{-- Sửa --}}
                                @if(!$hideForUser)
                                <td>
                                    <form method="GET" action="{{ route('cap-nhat-san', ['yard_id' => $yard->yard_id]) }}">
                                        <button type="submit" class="update-btn">Sửa</button>
                                    </form>
                                </td>
                                @endif

                                {{-- Xóa --}}
                                @if(!$hideForUser)
                                <td>
                                    <form method="POST" action="{{ route('xoa-san', ['yard_id' => $yard->yard_id, 'type_id' => request('type_id')]) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xoá sân này không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn">Xóa</button>
                                    </form>
                                </td>
                                @endif
                            @endif
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@endsection
