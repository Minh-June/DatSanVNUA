@extends('layouts.admin')

@section('title', 'Quản lý thời gian thuê sân')

@section('content')
    <!-- Hiển thị thông báo thành công -->
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

    <h3>Thời gian thuê sân</h3>
    
    <!-- Begin: Form chọn sân -->
    <div class="admin-time">
        <form method="POST" action="{{ route('search.time_slots') }}">
            @csrf
            <label for="san_id">Chọn sân:</label>
            <select class="admin-time-select" name="san_id" id="san_id">
                @foreach ($san_list as $san)
                    <option value="{{ $san->san_id }}" {{ (isset($selected_san_id) && $selected_san_id == $san->san_id) ? 'selected' : '' }}>
                        {{ $san->tensan }} - {{ $san->sosan }}
                    </option>
                @endforeach
            </select>
            <button class="admin-time-btn" type="submit" name="search_time_slots">Tìm kiếm</button>
        </form>
    </div>
    <!-- End: Form chọn sân -->

    <!-- Begin: Table danh sách khung giờ -->
    @if(isset($time_slots))
        @if ($time_slots->count() > 0)
            <table id='ListCustomers'>
                <tr>
                    <th>STT</th>
                    <th>Khung giờ</th>
                    <th>Giá tiền</th>
                    <th>Cập nhật</th>
                    <th>Xóa</th>
                </tr>
                @foreach ($time_slots as $index => $slot)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $slot->time_slot }}</td>
                    <td>{{ $slot->price }}</td>
                    <td>
                        <form method="GET" action="{{ route('cap-nhat-thoi-gian-san', $slot->time_slot_id) }}">
                            <button type="submit">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('delete-time-slot', $slot->time_slot_id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khung giờ này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </table>
        @else
            <p>Không có khung giờ nào được tìm thấy cho sân này.</p>
        @endif
    @endif
    <!-- End: Table danh sách khung giờ -->

@endsection
