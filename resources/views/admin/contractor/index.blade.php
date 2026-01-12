@extends('layouts.admin') 

@section('title', 'Thông tin đơn vị thầu')

@section('content')
@php
    use Carbon\Carbon;
    $maxDate = Carbon::now()->subYears(13)->format('Y-m-d');   
    $minDate = Carbon::now()->subYears(100)->format('Y-m-d'); 
    $user = auth()->user();
@endphp

@if ($errors->any())
    <script>
        @foreach ($errors->all() as $error)
            alert("{{ $error }}");
        @endforeach
    </script>
@endif

@if(session('success'))
    <script>alert("{{ session('success') }}");</script>
@endif

<h2>Thông tin đơn vị thầu</h2>

<div class="adminedit-contractor">
    <div class="adminedit-contractor-left">
        <h2 style="margin:-5px 0 15px 0;">Chủ thầu</h2>
        <div class="adminedit">
            <form method="post" action="{{ route('cap-nhat-thong-tin-don-vi-thau') }}">
                @csrf
                <input type="hidden" name="yard_id" value="{{ $yardId ?? '' }}">

                <div class="adminedit-form-group">
                    <label>Họ và tên:</label>
                    <input type="text" value="{{ $contractor->fullname ?? '' }}" disabled>
                </div>

                <div class="adminedit-form-group">
                    <label>Giới tính:</label>
                    <input type="text" value="{{ $contractor->gender ?? '' }}" disabled>
                </div>

                <div class="adminedit-form-group">
                    <label>Ngày sinh:</label>
                    <input type="date"
                        value="{{ $contractor->birthdate ?? '' }}"
                        min="{{ $minDate }}"
                        max="{{ $maxDate }}"
                        disabled>
                </div>

                @if($user->role == 0)
                    <div class="adminedit-form-group">
                        <label>Số điện thoại:</label>
                        <div class="adminedit-select-contractor">
                            <select id="user_id" name="user_id" required>
                                @foreach($contractors as $c)
                                    <option value="{{ $c->user_id }}" {{ $c->user_id == ($contractor->user_id ?? '') ? 'selected' : '' }}>
                                        {{ $c->phonenb }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @else
                    <div class="adminedit-form-group">
                        <label>Số điện thoại:</label>
                        <input type="text" value="{{ $contractor->phonenb ?? '' }}" disabled>
                    </div>
                @endif

                <div class="adminedit-form-group">
                    <label>Email:</label>
                    <input type="email" value="{{ $contractor->email ?? '' }}" disabled>
                </div>

                @if(auth()->user()->role == 0)
                    <div class="adminedit-button" style="margin:0;">
                        <button type="submit" class="update-btn">Cập nhật thông tin</button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <div class="adminedit-contractor-right">
        <h2 style="margin:-5px 0 15px 0;">Danh sách nhân viên</h2>

        <table id='ListCustomers'>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên nhân viên</th>
                    <th>Ngày sinh</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $index => $emp)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $emp->fullname }}</td>
                        <td>{{ \Carbon\Carbon::parse($emp->birthdate)->format('d/m/Y') }}</td>
                        <td>{{ $emp->phonenb }}</td>
                        <td>{{ $emp->email }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Đơn vị chưa có nhân viên nào</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="yard-statements"> 
    <h2>Thông số thống kê</h2>

    <div class="admin-top-bar-statement" style="margin-top:30px;">
        <div class="admin-top-bar">
            <div class="admin-search">
                <form method="GET" action="{{ route('thong-tin-don-vi-thau') }}">
                    <input type="hidden" name="user_id" value="{{ $contractor->user_id }}">

                    <label for="filter_type">Kiểu thống kê:</label>
                    <select name="filter_type" id="filter_type" onchange="toggleInputs()" required style="width: 102px; margin-bottom: 6px;">
                        <option value="date" {{ $filterType == 'date' ? 'selected' : '' }}>Theo ngày</option>
                        <option value="month" {{ $filterType == 'month' ? 'selected' : '' }}>Theo tháng</option>
                        <option value="year" {{ $filterType == 'year' ? 'selected' : '' }}>Theo năm</option>
                    </select>

                    <button type="submit" class="update-btn" style="margin-left:5px;">Xem báo cáo</button>

                    <div id="input-date" style="{{ $filterType != 'date' ? 'display:none;' : '' }}; margin-top:5px;">
                        <label for="date">Ngày:</label>
                        <input type="date" name="date" id="date" value="{{ request('date', date('Y-m-d')) }}">
                    </div>

                    <div id="input-month" style="{{ $filterType != 'month' ? 'display:none;' : '' }}; margin-top:5px;">
                        <label for="month">Tháng:</label>
                        <input type="month" name="month" id="month" value="{{ request('month', date('Y-m')) }}">
                    </div>

                    <div id="input-year" style="{{ $filterType != 'year' ? 'display:none;' : '' }}; margin-top:5px;">
                        <label for="year">Năm:</label>
                        <input type="number" name="year" id="year" min="2000" max="{{ date('Y') }}" value="{{ request('year', date('Y')) }}">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="adminedit-contractor-infor">
        <div class="adminedit-contractor-1">
            <h2>Đơn thuê cố định</h2>
            <p class="pick-order-1">{{ $fixedOrderCount }}</p>
        </div>

        <div class="adminedit-contractor-2">
            <h2>Đơn thuê lẻ</h2>
            <p class="pick-order-2">{{ $orderCount }}</p>
        </div>

        <div class="adminedit-contractor-3">
            <h2>Đơn mua hàng</h2>
            <p class="product-order">{{ $purchaseCount }}</p>
        </div>
    </div>
</div>

<script>
function toggleInputs() {
    const type = document.getElementById('filter_type').value;
    document.getElementById('input-date').style.display = type === 'date' ? 'block' : 'none';
    document.getElementById('input-month').style.display = type === 'month' ? 'block' : 'none';
    document.getElementById('input-year').style.display = type === 'year' ? 'block' : 'none';
}

const userRole = "{{ $user->role }}";
if (userRole == 0) {
    document.getElementById('user_id').addEventListener('change', function() {
        const userId = this.value;
        const yardId = "{{ $yardId ?? '' }}";
        if(!userId) return;

        window.location.href = "{{ route('thong-tin-don-vi-thau') }}" 
            + "?yard_id=" + yardId 
            + "&user_id=" + userId;
    });
}
</script>
@endsection
