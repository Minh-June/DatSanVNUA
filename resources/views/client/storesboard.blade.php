@extends('layouts.client.client')

@section('title', 'Danh sách các cửa hàng')

@section('content')
<div id="content" class="order-section">
    <h2 class="order-heading">MUA SẮM ĐỒ THỂ THAO</h2>

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif
    
    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <!-- Form tìm kiếm -->
    <form method="GET" action="{{ route('danh-sach-cua-hang') }}">
        <div class="news-method">
            <div class="news-search">
                <label for="searchStore">Tìm kiếm:</label>
                <input type="text" id="searchStore" name="name" placeholder="Nhập cửa hàng, sản phẩm..."
                    value="{{ request('name') }}">
                <button type="submit" class="order-football-btn" style="font-size: 18px;">Tìm kiếm</button>
            </div>
        </div>
    </form>
    
    <!-- Danh sách cửa hàng (Giao diện mới) -->
    <div class="store-list-container">
        @forelse($stores as $store)
            <div class="store-card">
                <!-- Header của Card -->
                <div class="store-header" onclick="window.location='{{ route('chi-tiet-cua-hang', $store->store_id) }}'">
                    <div class="store-avatar">
                        @if($store->user && $store->user->image)
                            <img src="{{ asset('storage/' . $store->user->image) }}" alt="{{ $store->name }}">
                        @else
                            <img src="{{ asset('images/default-avatar.png') }}" alt="Không có ảnh">
                        @endif
                    </div>
                    <div class="store-info">
                        <h3 class="store-name">{{ $store->name }}</h3>
                        <p class="store-owner">
                            {{ $store->user ? $store->user->fullname : 'Chưa xác định' }}
                        </p>
                    </div>
                </div>

                <!-- Gallery sản phẩm  -->
                <div class="store-products-gallery">
                    @if($store->products && $store->products->count() > 0)
                        @foreach($store->products as $product)
                            @php
                                // Lấy size có giá thấp nhất
                                $defaultSize = $product->sizes->sortBy('price')->first();
                                $displayPrice = $defaultSize->price ?? $product->price;
                            @endphp
                            <div class="product-item" onclick="window.location='{{ route('chi-tiet-san-pham', $product->product_id) }}'">
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image) }}" alt="{{ $product->name }}">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" alt="Không có ảnh">
                                @endif
                                <div class="product-info">
                                    <p class="product-name">{{ $product->name }}</p>
                                    <p class="product-price">{{ number_format($displayPrice, 0, ',', '.') }}đ</p>
                                    <form method="POST" action="{{ route('luu-san-pham-storesboard') }}">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                        <input type="hidden" name="store_id" value="{{ $product->store_id }}">
                                        <input type="hidden" name="name" value="{{ $product->name }}">
                                        <input type="hidden" name="price" value="{{ $displayPrice }}">
                                        <input type="hidden" name="image" value="{{ $product->images->first()->image ?? '' }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="size" value="{{ $defaultSize->name ?? '' }}">
                                        <input type="hidden" name="product_size_id" value="{{ $defaultSize->product_size_id ?? '' }}">
                                        <button type="submit" class="store-add-btn">Thêm vào giỏ hàng</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="no-products">Chưa có sản phẩm</p>
                    @endif
                </div>

                <!-- Nút hành động  -->
                <a href="{{ route('chi-tiet-cua-hang', $store->store_id) }}" class="store-action-btn">Đi đến cửa hàng</a>
            </div>
        @empty
            <p style="text-align:center; margin:169px 0;">Hiện chưa có cửa hàng nào hoạt động.</p>
        @endforelse
    </div>

    <!-- Pagination custom -->
    <ul class="pagination">
        {{-- Previous --}}
        <li class="pagination-item {{ $stores->onFirstPage() ? 'disabled' : '' }}">
            <a href="{{ $stores->previousPageUrl() ?? '#' }}" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-left"></i>
            </a>
        </li>

        <!-- Page numbers -->
        @for ($i = 1; $i <= $stores->lastPage(); $i++)
            @if ($i <= 5 || $i == $stores->lastPage())
                <li class="pagination-item {{ $stores->currentPage() == $i ? 'pagination-item--active' : '' }}">
                    <a href="{{ $stores->url($i) }}" class="pagination-item__link">{{ $i }}</a>
                </li>
                @if ($i == 5 && $stores->lastPage() > 6)
                    <li class="pagination-item"><span class="pagination-item__link">...</span></li>
                @endif
            @endif
        @endfor

        <!-- Next -->
        <li class="pagination-item {{ $stores->hasMorePages() ? '' : 'disabled' }}">
            <a href="{{ $stores->nextPageUrl() ?? '#' }}" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-right"></i>
            </a>
        </li>
    </ul>

</div>
@endsection