@extends('layouts.admin')

@section('title', 'Quản lý khung giờ cho thuê')

@section('content')
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

    <h2>Quản lý khung giờ - {{ $times->first()->yard->name ?? '' }}</h2>
    <div class="admin-top-bar">
        @if(request('yard_id'))
            <div class="admin-search">
                <form method="GET" action="{{ route('quan-ly-thoi-gian-san') }}">
                    <input type="hidden" name="yard_id" value="{{ request('yard_id') }}">
                    <label for="date">Chọn ngày:</label>
                    <input type="date" id="date" name="date" value="{{ request('date', date('Y-m-d')) }}">
                    <button class="update-btn" type="submit">Tìm kiếm</button>
                </form>
            </div>
        @endif
        <div class="admin-add-btn">
            <a class="update-btn" href="{{ route('them-thoi-gian-san', ['yard_id' => request('yard_id')]) }}">Thêm khung giờ</a>
        </div>
    </div>

        <!-- Hiển thị bảng dữ liệu khi đã chọn sân và lọc theo ngày -->
        <table id='ListCustomers'>
            <thead>
                <tr>
                <th>STT</th>
                <th>Khung giờ</th>
                <th>Giá tiền</th>
                <th colspan="2">Tuỳ chọn</th>
                </tr>
            </thead>
            <tbody>
                @foreach($times as $index => $time)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $time->time }}</td>
                        <td>{{ number_format($time->price, 0, ',', '.') }}đ</td>
                        <td>
                            <form method="GET" action="{{ route('cap-nhat-thoi-gian-san', ['time_id' => $time->time_id]) }}">
                                <button type="submit" class="update-btn">Sửa</button>
                            </form>
                        </td>
                        <td>
                        <form method="POST" action="{{ route('xoa-thoi-gian-san', ['time_id' => $time->time_id, 'yard_id' => request('yard_id')]) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xoá khung giờ này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endsection
