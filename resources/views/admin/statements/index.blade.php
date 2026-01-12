@extends('layouts.admin')

@section('title', 'Quản lý thống kê, báo cáo')

@section('content')
    <h2>Thống kê doanh thu</h2>

    <div class="admin-top-bar-statement">
        <div class="admin-top-bar">
                <div class="admin-search">
                    <form method="GET" action="{{ route('thong-ke-bao-cao') }}">
                        <label for="filter_type">Kiểu thống kê:</label>
                        <select name="filter_type" id="filter_type" onchange="toggleInputs()" required style="width: 102px; margin-bottom: 6px;">
                            <option value="date" {{ request('filter_type') == 'date' ? 'selected' : '' }}>Theo ngày</option>
                            <option value="month" {{ request('filter_type') == 'month' ? 'selected' : '' }}>Theo tháng</option>
                            <option value="year" {{ request('filter_type') == 'year' ? 'selected' : '' }}>Theo năm</option>
                        </select>

                        <button type="submit" class="update-btn" style="margin-left:5px;">Xem báo cáo</button>
                    
                        <div id="input-date" style="{{ request('filter_type') != 'date' ? 'display:none;' : '' }}; margin-top:5px;">
                            <label for="date">Ngày:</label>
                            <input type="date" style="width: 169px;" name="date" id="date" value="{{ request('date', date('Y-m-d')) }}">
                        </div>

                        <div id="input-month" style="{{ request('filter_type') != 'month' ? 'display:none;' : '' }}; margin-top:5px;">
                            <label for="month">Tháng:</label>
                            <input type="month" style="width: 164px;" name="month" id="month" value="{{ request('month', date('Y-m')) }}">
                        </div>
                        
                        <div id="input-year" style="{{ request('filter_type') != 'year' ? 'display:none;' : '' }}; margin-top:5px;">
                            <label for="year">Năm:</label>
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
    </div>

    @if(isset($totalRevenue))
        <h2 style="color:var(--primary-color);">Tổng doanh thu: {{ number_format($totalRevenue, 0, ',', '.') }}đ</h2>

        @if($groupFixed->isNotEmpty())
            <h2 style="text-align: left; margin-left: 40px;">Doanh thu thuê sân cố định</h2>
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
                    @php $sttFixed = 1; @endphp

                    @foreach($groupFixed as $typeName => $yards)
                        @php $rowCount = $yards->count(); $firstTypeRow = true; @endphp
                        @foreach($yards as $yardName => $data)
                            <tr>
                                @if($firstTypeRow)
                                    <td rowspan="{{ $rowCount }}">{{ $sttFixed++ }}</td>
                                    <td rowspan="{{ $rowCount }}" class="left-align">{{ $typeName ?? 'Loại sân không xác định' }}</td>
                                    @php $firstTypeRow = false; @endphp
                                @endif
                                <td class="left-align">{{ $yardName ?? 'Sân không tồn tại' }}</td>
                                <td>
                                    <a>
                                        {{ $data['booking_count'] }}
                                    </a>
                                </td>
                                <td>{{ number_format($data['total_revenue'], 0, ',', '.') }}đ</td>
                            </tr>
                        @endforeach
                    @endforeach

                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Tổng doanh thu:</strong></td> 
                        <td>{{ number_format($groupFixed->sum(fn($yards) => $yards->sum(fn($data) => $data['total_revenue'])), 0, ',', '.') }}đ</td>
                    </tr>
                </tbody>
            </table>
        @endif

        {{-- Thuê sân lẻ --}}
        @if($groupByTypeThenYard->isNotEmpty())
            <h2 style="text-align: left; margin-left: 40px;">Doanh thu thuê sân lẻ</h2>
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
                @php $stt = 1; @endphp
                @foreach($groupByTypeThenYard as $typeName => $yards)
                    @php
                        $rowCount = $yards->count();
                        $firstTypeRow = true;
                    @endphp
                    @foreach($yards as $yardName => $data)
                        <tr>
                            @if($firstTypeRow)
                                <td rowspan="{{ $rowCount }}">{{ $stt++ }}</td>
                                <td class="left-align" rowspan="{{ $rowCount }}">{{ $typeName ?? 'Loại sân không tồn tại' }}</td>
                                @php $firstTypeRow = false; @endphp
                            @endif
                            <td class="left-align">{{ $yardName ?? 'Sân không tồn tại' }}</td>
                            <td>
                                <a>
                                    {{ $data['booking_count'] }}
                                </a>
                            </td>
                            <td>{{ number_format($data['total_revenue'], 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                @endforeach
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Tổng doanh thu:</strong></td> 
                        <td>{{ number_format($groupByTypeThenYard->sum(fn($yards) => $yards->sum(fn($data) => $data['total_revenue'])), 0, ',', '.') }}đ</td>
                    </tr>
                </tbody>
            </table>
        @endif

        {{-- Bán hàng --}}
        @php $user = auth()->user(); @endphp
        @if($user->role != 0 && $groupProduct->isNotEmpty())
            <h2 style="text-align: left; margin-left: 40px;">Doanh thu bán hàng</h2>
            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Loại sản phẩm</th>
                        <th>Sản phẩm</th>
                        <th>Số đơn đặt</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    @php $stt = 1; @endphp
                    @foreach($groupProduct as $data)
                        <tr>
                            <td>{{ $stt++ }}</td>
                            <td class="left-align">{{ $data['type_name'] }}</td>
                            <td class="left-align">{{ $data['product_name'] }}</td>
                            <td>
                                <a>
                                    {{ $data['total_orders'] }}
                                </a>
                            </td>
                            <td>{{ number_format($data['total_revenue'], 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Tổng doanh thu:</strong></td> 
                        <td>{{ number_format($groupProduct->sum('total_revenue'), 0, ',', '.') }}đ</td>
                    </tr>
                </tbody>
            </table>
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
