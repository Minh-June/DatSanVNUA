@extends('layouts.admin')

@section('title', 'Quản lý thống kê, báo cáo')

@section('content')
    <h2>Thống kê doanh thu</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="{{ route('thong-ke-bao-cao') }}">
                <label for="filter_type">Chọn kiểu thống kê:</label>
                <select name="filter_type" id="filter_type" onchange="toggleInputs()" required style="width: 102px; margin-bottom: 6px;">
                    <option value="date" {{ request('filter_type') == 'date' ? 'selected' : '' }}>Theo ngày</option>
                    <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Theo tháng</option>
                    <option value="year" {{ request('filter_type') == 'year' ? 'selected' : '' }}>Theo năm</option>
                </select>

                <button type="submit" class="update-btn">Xem báo cáo</button>
            
                <div id="input-date" style="{{ request('filter_type') != 'date' ? 'display:none;' : '' }}">
                    <label for="date">Chọn ngày:</label>
                    <input type="date" style="width: 169px;" name="date" id="date" value="{{ request('date', date('Y-m-d')) }}">
                </div>

                <div id="input-month" style="{{ request('filter_type') != 'month' ? 'display:none;' : '' }}">
                    <label for="month">Chọn tháng:</label>
                    <input type="month" style="width: 164px;" name="month" id="month" value="{{ request('month', date('Y-m')) }}">
                </div>
                
                <div id="input-year" style="{{ request('filter_type') != 'year' ? 'display:none;' : '' }}">
                    <label for="year">Chọn năm:</label>
                    <input type="number" name="year" id="year" min="2000" max="{{ date('Y') }}" value="{{ request('year', date('Y')) }}">
                </div>
            </form>
        </div>
        
        <div class="admin-add-btn">
            <form method="GET" action="{{ route('xuat-excel-doanh-thu') }}">
                <input type="hidden" name="filter_type" value="{{ request('filter_type') }}">
                <input type="hidden" name="date" value="{{ request('date') }}">
                <input type="hidden" name="month" value="{{ request('month') }}">
                <input type="hidden" name="year" value="{{ request('year') }}">
                <button type="submit" class="delete-btn">
                    <i class="fa-solid fa-file-export"></i>
                    Xuất Excel
                </button>
            </form>
        </div>
    </div>

    @if(isset($totalRevenue))
        <h2>Tổng doanh thu: {{ number_format($totalRevenue, 0, ',', '.') }}đ</h2>

        @if($totalRevenue > 0)
            <h2>Doanh thu từng sân</h2>

            <div class="admin-top-bar">
                <div class="admin-search">
                    <form method="GET" action="{{ route('thong-ke-bao-cao') }}">
                        <input type="hidden" name="filter_type" value="{{ request('filter_type') }}">
                        <input type="hidden" name="date" value="{{ request('date') }}">
                        <input type="hidden" name="month" value="{{ request('month') }}">
                        <input type="hidden" name="year" value="{{ request('year') }}">

                        <input type="text" id="keyword" name="keyword" placeholder="Nhập tên sân cần tìm" value="{{ request('keyword') }}">
                        <button class="update-btn" type="submit">Tìm kiếm</button>
                    </form>
                </div>
            </div>

            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Loại sân</th>
                        <th>Tên sân</th>
                        <th>Số đơn đặt</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                @php
                    $stt = 1;
                @endphp

                @foreach($groupByTypeThenYard as $typeName => $yards)
                    @php
                        $rowCount = $yards->count();
                        $firstTypeRow = true;
                    @endphp

                    @foreach($yards as $yardName => $data)
                        <tr>
                            @if($firstTypeRow)
                                <td rowspan="{{ $rowCount }}">{{ $stt++ }}</td>
                            @endif

                            @if($firstTypeRow)
                                <td class="left-align" rowspan="{{ $rowCount }}">{{ $typeName ?? 'Loại sân không tồn tại' }}</td>
                                @php $firstTypeRow = false; @endphp
                            @endif

                            <td class="left-align">{{ $yardName ?? 'Sân không tồn tại' }}</td>

                            <td>
                                <a href="{{ route('quan-ly-don-dat-san', [
                                    'yard_name' => $yardName,
                                    'type_name' => $typeName,
                                    'status' => 1
                                ]) }}">
                                    {{ $data['booking_count'] }}
                                </a>
                            </td>
                            <td>{{ number_format($data['total_revenue'], 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
        @else
            <h2 style="font-weight: normal; font-size: 18px;">Hiện chưa có dữ liệu báo cáo</h2>
        @endif
    @endif

    <script>
        function toggleInputs() {
            const filterType = document.getElementById('filter_type').value;
            document.getElementById('input-date').style.display = filterType === 'date' ? 'inline-block' : 'none';
            document.getElementById('input-month').style.display = filterType === 'month' ? 'inline-block' : 'none';
            document.getElementById('input-year').style.display = filterType === 'year' ? 'inline-block' : 'none';
        }

        // Gọi khi trang load
        document.addEventListener('DOMContentLoaded', toggleInputs);
    </script>
@endsection
