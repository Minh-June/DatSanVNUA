@extends('layouts.client.client')

@section('title', 'Giỏ hàng sản phẩm')

@section('content')
<div id="content" class="order-section">
    <h2 class="order-heading">GIỎ HÀNG</h2>

    @php
        $buys = session('buys', []);
        $groupedBuys = collect($buys)->groupBy('store_id');
        $totalPriceAll = array_sum(array_map(fn($i)=>$i['price']*$i['quantity'],$buys));
    @endphp

    @if(count($buys) > 0)
        <div class="cart-detail-wrapper">
            <!-- Bên trái: Bảng sản phẩm -->
            <div class="cart-detail-left">
                <table id="ListCustomers" style="margin-bottom:60px;">
                    <thead>
                        <tr>
                            <th colspan="2">Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Tùy chọn</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($groupedBuys as $storeId => $storeItems)
                            @php
                                $store = \App\Models\Store::find($storeId);
                                $storeName = $store->name ?? 'Shop không xác định';
                                $productsGrouped = collect($storeItems)->groupBy(fn($item) => $item['product_id'].'-'.$item['price'].'-'.($item['product_size_id'] ?? ''));
                            @endphp

                            <!-- Tên shop -->
                            <tr class="name-shop-cart-pay" onclick="window.location='{{ route('chi-tiet-cua-hang', $storeId) }}'" style="cursor:pointer;">
                                <td colspan="6" class="left-align">
                                    <img src="{{ $store && $store->user && $store->user->image ? asset('storage/'.$store->user->image) : asset('images/default-avatar.png') }}" 
                                        alt="Shop" style="height:40px;width:40px;border-radius:50%;margin-right:10px;">
                                    <span>{{ $storeName }}</span>
                                </td>
                            </tr>

                            @php $shopTotal = 0; @endphp

                            @foreach($productsGrouped as $key => $items)
                                @php
                                    $firstItem = $items->first();
                                    $totalQuantity = $items->sum('quantity');
                                    $totalPrice = $items->sum(fn($i)=>$i['price']*$i['quantity']);
                                    $shopTotal += $totalPrice;

                                    // Xử lý tên sản phẩm + size
                                    $fullName = $firstItem['name'];
                                    $sizeWords = [];
                                    if(!empty($firstItem['product_size_id'])){
                                        $size = \App\Models\ProductSize::find($firstItem['product_size_id']);
                                        $sizeName = $size ? $size->name : '';
                                        if($sizeName) {
                                            $sizeWords = ['-', 'Size', $sizeName];
                                        }
                                    }

                                    // Tách từ
                                    $words = explode(' ', $fullName);
                                    $chunks = array_chunk($words, 6);

                                    // Nếu có size, thêm vào dòng cuối
                                    if(count($sizeWords)){
                                        $chunks[] = $sizeWords;
                                    }

                                    $sessionKey = null;
                                    foreach($buys as $k => $b){
                                        if($b['product_id']==$firstItem['product_id'] && $b['price']==$firstItem['price'] && ($b['product_size_id'] ?? '')==($firstItem['product_size_id'] ?? '')){
                                            $sessionKey = $k;
                                            break;
                                        }
                                    }
                                @endphp

                                <tr id="row_{{ $sessionKey }}" data-group-key="{{ $storeId }}_{{ $firstItem['product_id'] }}">
                                    <td style="cursor:pointer;" onclick="window.location='{{ route('chi-tiet-san-pham',$firstItem['product_id']) }}'">
                                        <img src="{{ asset('storage/' . ($firstItem['image'] ?? 'image/football.jpg')) }}" width="80">
                                    </td>
                                    <td class="left-align product-name-cart" style="cursor:pointer;" onclick="window.location='{{ route('chi-tiet-san-pham',$firstItem['product_id']) }}'">
                                        @foreach($chunks as $chunk)
                                            {{ implode(' ', $chunk) }}<br>
                                        @endforeach
                                    </td>

                                    <td>{{ number_format($firstItem['price']) }}đ</td>
                                    <td>
                                        <div class="product-quantity" style="margin:0 0 0 20px;">
                                            <button type="button" class="quantity-btn" onclick="changeQuantity('{{ $sessionKey }}', {{ $firstItem['price'] }}, -1)">-</button>
                                            <input class="quantity-cart" id="quantityInput_{{ $sessionKey }}" value="{{ $totalQuantity }}" readonly>
                                            <button type="button" class="quantity-btn" onclick="changeQuantity('{{ $sessionKey }}', {{ $firstItem['price'] }}, 1)">+</button>
                                        </div>
                                    </td>
                                    <td id="totalPrice_{{ $sessionKey }}">{{ number_format($totalPrice) }}đ</td>
                                    <td style="text-align:center;">
                                        <button type="button" class="delete-btn" onclick="deleteItemGroup('{{ $storeId }}_{{ $firstItem['product_id'] }}')">Xóa</button>
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="4" style="font-size:17px; text-align:right; font-weight:bold;">Tổng tiền:</td>
                                <td colspan="2" style="font-size:17px; font-weight:bold;" id="shopTotal_{{ $storeId }}">0đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="cart-detail-right">
                <div class="cart-summary">
                    <h3>Tổng cộng giỏ hàng</h3>
                    <p><strong class="cart-dash">Tổng</strong> <span id="cartTotal">{{ number_format($totalPriceAll) }}đ</span></p>
                </div>
            </div>
        </div>

        <div class="footer-link" style="margin-bottom:40px;">
            <a href="{{ route('thanh-toan-gio-hang') }}">
                <button type="button" class="order-football-btn">Tiến hành thanh toán</button>
            </a>
        </div>
    @else
        <p style="
            text-align:center;
            height:520px;
            display:flex;
            justify-content:center;
            align-items:center;
        ">
            Giỏ hàng của bạn trống.
        </p>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    if({{ count($buys) }}===0){
        alert('Vui lòng quay lại trang mua sắm mua sản phẩm !');
        window.location.href = "{{ route('danh-sach-cua-hang') }}";
    }
    updateCartTotal();
});

function updateCartTotal(){
    let cartTotal = 0;
    let shopTotals = {};
    document.querySelectorAll('[id^="totalPrice_"]').forEach(cell=>{
        const groupKey = cell.closest('tr').dataset.groupKey;
        if(!groupKey) return;
        const storeId = groupKey.split('_')[0];
        const value = parseInt(cell.innerText.replace(/\D/g,''))||0;
        cartTotal += value;
        if(!shopTotals[storeId]) shopTotals[storeId]=0;
        shopTotals[storeId]+=value;
    });

    for(const storeId in shopTotals){
        const el = document.getElementById(`shopTotal_${storeId}`);
        if(el) el.innerText = shopTotals[storeId].toLocaleString('vi-VN')+'đ';
    }

    const cartEl = document.getElementById('cartTotal');
    if(cartEl) cartEl.innerText = cartTotal.toLocaleString('vi-VN')+'đ';
}

function changeQuantity(key, price, delta){
    const qtyInput = document.getElementById(`quantityInput_${key}`);
    const totalPriceCell = document.getElementById(`totalPrice_${key}`);
    if(!qtyInput||!totalPriceCell) return;

    let qty = parseInt(qtyInput.value)||1;
    qty+=delta;
    if(qty<1) qty=1;
    qtyInput.value=qty;

    totalPriceCell.innerText = (qty*price).toLocaleString('vi-VN')+'đ';

    fetch("{{ route('cap-nhat-so-luong') }}",{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({index:key, quantity:qty})
    }).then(res=>res.json())
      .then(data=>{ if(!data.success) alert('Cập nhật thất bại!'); updateCartTotal(); })
      .catch(err=>console.error(err));
}

function deleteItemGroup(groupKey){
    if(!confirm('Bạn có chắc muốn xóa toàn bộ sản phẩm này khỏi giỏ hàng ?')) return;

    const rows = document.querySelectorAll(`tr[data-group-key="${groupKey}"]`);
    let indexes=[];
    rows.forEach(r=>{
        const id=r.id.replace('row_','');
        if(id) indexes.push(id);
    });

    let deletedCount=0;
    indexes.forEach(index=>{
        fetch("{{ route('xoa-san-pham-tam-thoi') }}",{
            method:'DELETE',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({index:index})
        }).then(res=>res.json())
          .then(data=>{
            if(data.success){
                const row=document.getElementById(`row_${index}`);
                if(row) row.remove();
                updateCartTotal();
            }
            deletedCount++;
            if(deletedCount===indexes.length) location.reload();
          }).catch(err=>console.error(err));
    });
}
</script>
@endsection
