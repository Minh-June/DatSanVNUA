@extends('layouts.admin')

@section('title', 'Cập nhật chi tiết đơn đặt mua hàng')

@section('content')
@if(session('price_change_message'))
    <script>alert("{{ session('price_change_message') }}");</script>
@endif
@if(session('error'))
    <script>alert("{{ session('error') }}");</script>
@endif

<h2>Chi tiết đơn mua hàng</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="{{ route('quan-ly-don-mua-hang') }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
    <div class="admin-add-btn">
    </div>
</div>

@php $currentUser = auth()->user(); @endphp

<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Sản phẩm</th>
            <th>Size</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            {{-- Nếu không phải nhân viên mới hiển thị cột tùy chọn --}}
            @if($currentUser->role != 3)
                <th colspan="2">Tùy chọn</th>
            @endif
        </tr>
    </thead>
    <tbody>
    @foreach($order->orderDetails as $detail)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $detail->product->name ?? 'Không xác định' }}</td>
        <td>{{ $detail->size->name ?? 'Không có' }}</td>

        {{-- Đơn giá --}}
        <td>{{ number_format($detail->price, 0, ',', '.') }}đ</td>

        {{-- Số lượng --}}
        <td>{{ $detail->quantity }}</td>

        {{-- Thành tiền --}}
        <td>{{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}đ</td>

        @if($currentUser->role != 3)
            <td>
                <form action="{{ route('cap-nhat-chi-tiet-don-mua-hang', $detail->product_order_detail_id) }}" method="GET">
                    <button type="submit" class="update-btn">Sửa</button>
                </form>
            </td>
            <td>
                <form method="POST" action="{{ route('xoa-chi-tiet-don-mua-hang', $detail->product_order_detail_id) }}" onsubmit="return confirm('Bạn có chắc muốn xóa chi tiết đơn này?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-btn">Xóa</button>
                </form>
            </td>
        @endif
    </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align:right;"><strong>Tổng tiền:</strong></td>
            <td colspan="{{ $currentUser->role != 3 ? 3 : 1 }}">
                <strong>
                    {{ number_format($order->orderDetails->sum(function($d){ return $d->price * $d->quantity; }), 0, ',', '.') }}đ
                </strong>
            </td>
        </tr>
    </tfoot>
</table>

@if(isset($editDetail))
<h2 style="margin-top:30px;">Cập nhật thông tin chi tiết đơn</h2>

<div class="adminedit">
    <form method="POST" action="{{ route('update-chi-tiet-don-mua-hang', $editDetail->product_order_detail_id) }}">
        @csrf
        {{-- SẢN PHẨM --}}
        <div class="adminedit-form-group">
            <label>Sản phẩm:</label>
            <select name="product_id" id="productSelect" required>
                @foreach($products as $p)
                    <option value="{{ $p->product_id }}"
                        {{ $editDetail->product_id == $p->product_id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- SIZE --}}
        <div class="adminedit-form-group">
            <label>Size:</label>
            <select name="product_size_id" id="sizeSelect"
                {{ $editDetail->product->sizes->count() ? '' : 'disabled' }}>

                @foreach($editDetail->product->sizes as $s)
                    <option value="{{ $s->product_size_id }}"
                        data-price="{{ $s->price }}"
                        {{ $editDetail->product_size_id == $s->product_size_id ? 'selected' : '' }}>
                        {{ $s->name }}
                    </option>
                @endforeach
            </select>
        </div>
        {{-- ĐƠN GIÁ --}}
        <div class="adminedit-form-group">
            <label>Đơn giá:</label>
            <input type="text" id="priceInput"
                value="{{ number_format($editDetail->price,0,',','.') }}đ"
                disabled>
        </div>
        {{-- SỐ LƯỢNG --}}
        <div class="adminedit-form-group">
            <label>Số lượng:</label>
            <input type="number" id="quantityInput" name="quantity"
                value="{{ $editDetail->quantity }}" min="1" required>
        </div>
        {{-- THÀNH TIỀN --}}
        <div class="adminedit-form-group">
            <label>Thành tiền:</label>
            <input type="text" id="totalInput"
                value="{{ number_format($editDetail->price * $editDetail->quantity,0,',','.') }}đ"
                disabled>
        </div>
        <div class="adminedit-button">
            <button type="submit" class="update-btn">Cập nhật thông tin</button>
        </div>
    </form>
</div>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Đổi sản phẩm → load size + giá
$('#productSelect').on('change', function () {
    let productId = $(this).val();

    $.ajax({
        url: '/admin/ajax/product-info/' + productId,
        type: 'GET',
        success: function (data) {

            $('#sizeSelect').empty();

            // Có size
            if (data.sizes.length > 0) {
                $('#sizeSelect').prop('disabled', false);

                data.sizes.forEach(s => {
                    $('#sizeSelect').append(`
                        <option value="${s.product_size_id}" data-price="${s.price}">
                            ${s.name} (${parseInt(s.price).toLocaleString('vi-VN')}đ)
                        </option>
                    `);
                });

                let price = data.sizes[0].price;
                $('#priceInput').val(price.toLocaleString('vi-VN') + 'đ');

            } else {
                // Không có size
                $('#sizeSelect').prop('disabled', true);

                let price = data.product.price;
                $('#priceInput').val(price.toLocaleString('vi-VN') + 'đ');
            }

            updateTotal();
        }
    });
});

// Đổi size
$('#sizeSelect').on('change', function () {
    let price = $(this).find(':selected').data('price') || 0;
    $('#priceInput').val(price.toLocaleString('vi-VN') + 'đ');
    updateTotal();
});

// Đổi số lượng
$('#quantityInput').on('input', function () {
    updateTotal();
});

// Tính thành tiền
function updateTotal() {
    let price = parseInt($('#priceInput').val().replace(/\D/g, '')) || 0;
    let qty = parseInt($('#quantityInput').val()) || 1;
    let total = price * qty;

    $('#totalInput').val(total.toLocaleString('vi-VN') + 'đ');
}
</script>

@endsection
