@extends('layouts.admin')

@section('title', 'Danh sách tin tức')

@section('content')
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <h2>Danh sách bài đăng tin tức</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="{{ route('quan-ly-tin-tuc') }}">
                <!-- Input chọn ngày -->
                <input type="date" name="date" value="{{ request('date', $date) }}">
                
                <!-- Input từ khóa -->
                <input type="text" name="search" placeholder="Tìm kiếm tin tức..." value="{{ request('search') }}">

                <button class="update-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>
            
        <div class="admin-add-btn">
            <a style='margin-right: 10px;' class="update-btn" href="{{ route('them-tin-tuc') }}">Đăng tin tức mới</a>
            <a class="update-btn" href="{{ route('quan-ly-loai-tin-tuc') }}">Danh sách loại tin tức</a>
        </div>
    </div>

    <table id="ListCustomers">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ngày đăng</th>
                <th>Người đăng</th>
                <th>Số điện thoại</th>
                <th>Loại tin</th>
                <th>Tiêu đề</th>
                <th>Thông tin</th>
                @if(auth()->user()->role != 3)
                    <th colspan='2'>Tuỳ chọn</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($newsList as $news)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $news->post_at ? \Carbon\Carbon::parse($news->post_at)->format('d/m/Y') : '' }}</td>
                <td class="left-align">
                    @php
                        $fullname = $news->user ? $news->user->fullname : 'Chưa xác định';
                        $words = explode(' ', $fullname); 
                        $chunks = array_chunk($words, 4); 
                    @endphp

                    @foreach($chunks as $chunk)
                        {{ implode(' ', $chunk) }}<br>
                    @endforeach
                </td>
                <td>{{ $news->user ? $news->user->phonenb : 'Chưa xác định' }}</td>
                <td>{{ $news->type ? $news->type->name : 'Chưa xác định' }}</td>
                <td class="left-align">
                    @php
                        $words = explode(' ', $news->title); 
                        $chunks = array_chunk($words, 5); 
                    @endphp

                    @foreach($chunks as $chunk)
                        {{ implode(' ', $chunk) }}<br>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('cap-nhat-tin-tuc', $news->news_id) }}">
                        Nội dung
                    </a>
                </td>

                @if(auth()->user()->role != 3)
                    @if(auth()->user()->role == 0 && $news->user && in_array($news->user->role, [2,3]))
                        <td colspan="2" style="text-align:center;">Đối tác đăng</td>
                    @else
                        {{-- Cập nhật trạng thái --}}
                        <td>
                            <form method="POST" action="{{ route('cap-nhat-trang-thai-tin-tuc') }}">
                                @csrf
                                <input type="hidden" name="news_id" value="{{ $news->news_id }}">
                                <select name="status">
                                    <option value="0" {{ $news->status == 0 ? 'selected' : '' }}>Đang hiện</option>
                                    <option value="1" {{ $news->status == 1 ? 'selected' : '' }}>Đã ẩn</option>
                                </select><br>
                                <button type="submit" class="update-btn">Cập nhật</button>
                            </form>
                        </td>
                        {{-- Xóa --}}
                        <td>
                            <form action="{{ route('xoa-tin-tuc', $news->news_id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    @endif
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ auth()->user()->role == 3 ? 7 : 9 }}" style="text-align:center;">Chưa có tin tức nào</td>
            </tr>
            @endforelse
        </tbody>
    </table>
@endsection
