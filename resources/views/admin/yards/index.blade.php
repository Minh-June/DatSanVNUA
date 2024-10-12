@extends('layouts.admin')

@section('title', 'Danh sách sân')

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

    <div class="admin-section">
        <h3>Danh sách các sân đang có</h3>

        <!-- Hiển thị bảng dữ liệu -->
        <table id='ListCustomers'>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sân</th>
                    <th>Số sân</th>
                    <th>Cập nhật</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                @foreach($yards as $key => $yard)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $yard->tensan }}</td>
                        <td>{{ $yard->sosan }}</td>
                        <td>
                            <form method="GET" action="{{ route('cap-nhat-san', ['san_id' => $yard->san_id]) }}">
                                <button type="submit">Sửa</button>
                            </form>
                        </td>                                      
                        <td>
                            <form method="POST" action="{{ route('delete-yard', $yard->san_id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sân này không?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Xóa</button>
                            </form>
                        </td>                                                                           
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
