@extends('layouts.client.client')

@section('title', $store->name ?? 'Trang chi tiết cửa hàng')

@section('css')
<link rel="stylesheet" href="{{ asset('css/store-detail.css') }}">
@endsection

@section('content')
<div id="content" class="order-section">
    <h2 class="order-heading">CỬA HÀNG</h2>

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif
    
    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <!-- Thông tin chủ shop -->
    <div class="content-shop">
        <div class="store-info-header">
            <div class="store-info-left">
                @if($store->user && $store->user->image)
                    <img src="{{ asset('storage/' . $store->user->image) }}" alt="Ảnh chủ shop">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Không có ảnh">
                @endif
                <div class="store-info-text">
                    <p>{{ $store->name ?? 'Shop thể thao' }}</p>
                    <p>
                        {{ $store->user ? $store->user->fullname : 'Chưa xác định' }}
                    </p>
                </div>
            </div>

            <div class="store-info-right">
                {{-- Số điện thoại --}}
                @if($store->user && $store->user->phonenb)
                    <p class="store-phone">
                        <i class="fa-solid fa-phone" style="margin: 0 7px 10px 0;"></i>
                        Liên hệ: {{ $store->user->phonenb }}
                    </p>
                @endif
                <p>
                    <i class="fa-solid fa-shop"></i>
                    Sản phẩm: {{ $products->total() }}
                </p>
            </div>
        </div>
    </div>

    <div class="shop-layout">
        <!-- Danh mục sản phẩm -->
        <div class="shop-sidebar">
            <p class="sidebar-title">
                <i class="fa-solid fa-bars"></i>
                Danh mục sản phẩm
            </p>
            <ul class="sidebar-list">
                <li>
                    <a href="{{ route('chi-tiet-cua-hang', $store->store_id) }}" class="sidebar-link {{ request('type') ? '' : 'active' }}">Tất cả</a>
                </li>
                @foreach($productTypes as $type)
                    <li>
                        <a href="{{ route('chi-tiet-cua-hang', [$store->store_id, 'type' => $type->product_type_id]) }}" 
                           class="sidebar-link {{ request('type') == $type->product_type_id ? 'active' : '' }}">
                            {{ $type->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="shop-main">
            <div class="shop-filter">
                <form method="GET" action="{{ route('chi-tiet-cua-hang', $store->store_id) }}" class="filter-form" id="filterForm">
                    <p>Tìm kiếm:</p>
                    <input type="text" name="search" placeholder="Mã sản phẩm, tên sản phẩm..." value="{{ request('search') }}" class="filter-input">
                    <button type="submit" class="update-btn" style="padding:7px 13px; margin-left:5px;">Tìm kiếm</button>

                    <p>Sắp xếp theo:</p>
                    <select name="sort" class="filter-select" id="sortSelect">
                        <option value="">Mức giá</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Mức giá: Thấp → Cao</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Mức giá: Cao → Thấp</option>
                    </select>
                </form>
            </div>

            <div class="store-products-gallery">
                @forelse($products as $product)
                    <div class="product-item" onclick="window.location='{{ route('chi-tiet-san-pham', $product->product_id) }}'">
                        @php
                            $img = $productImages[$product->name] ?? null;
                            $defaultSize = $product->sizes->sortBy('price')->first();
                            $displayPrice = $defaultSize->price ?? $product->price;
                        @endphp
                        <img src="{{ $img ? asset('storage/' . $img) : asset('images/no-image.png') }}" alt="{{ $product->name }}">

                        <div class="product-info">
                            <p class="product-name">{{ $product->name }}</p>
                            <p class="product-price">{{ number_format($displayPrice, 0, ',', '.') }}đ</p>

                            <!-- Form POST thêm vào giỏ hàng -->
                            <form method="POST" action="{{ route('luu-san-pham-storedetail') }}">
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
                @empty
                    <p class="no-products">Hiện chưa có sản phẩm</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <ul class="pagination">
        <li class="pagination-item {{ $products->onFirstPage() ? 'disabled' : '' }}">
            <a href="{{ $products->previousPageUrl() ?? '#' }}" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-left"></i>
            </a>
        </li>

        @for ($i = 1; $i <= $products->lastPage(); $i++)
            @if ($i <= 5 || $i == $products->lastPage())
                <li class="pagination-item {{ $products->currentPage() == $i ? 'pagination-item--active' : '' }}">
                    <a href="{{ $products->url($i) }}" class="pagination-item__link">{{ $i }}</a>
                </li>
                @if ($i == 5 && $products->lastPage() > 6)
                    <li class="pagination-item"><span class="pagination-item__link">...</span></li>
                @endif
            @endif
        @endfor

        <li class="pagination-item {{ $products->hasMorePages() ? '' : 'disabled' }}">
            <a href="{{ $products->nextPageUrl() ?? '#' }}" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-right"></i>
            </a>
        </li>
    </ul>
</div>

<script>
    document.getElementById('sortSelect').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
</script>
@endsection
