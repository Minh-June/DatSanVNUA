@extends('layouts.client.client')

@section('title', $news->title ?? 'Chi tiết tin tức thể thao')

@section('content')
<div id="content" class="order-section">
    <h2 class="order-heading">TIN TỨC THỂ THAO</h2>
    <div class="news-detail-wrapper">

        {{-- Bên trái: Nội dung chi tiết --}}
        <div class="news-detail-left">
            <div class="news-header-detail">
                <h1 class="news-title-detail">{{ $news->title }}</h1>
                <p class="news-meta-detail">
                    <span>Loại tin tức: {{ $news->type->name }}</span>
                    | 
                    <span>Người đăng: {{ $news->user->fullname ?? 'Ẩn danh' }}</span>
                    | 
                    <span>Ngày đăng: {{ date('d/m/Y', strtotime($news->post_at)) }}</span>
                </p>
            </div>
            <div class="news-contents">
                @foreach($news->contents as $content)
                    {{-- Hiện text (nếu có) trước --}}
                    @if($content->content)
                        <div class="news-text">
                            {!! nl2br(e($content->content)) !!}
                        </div>
                    @endif

                    {{-- Sau đó hiện ảnh và chú thích (nếu có) --}}
                    @if($content->image)
                        <div class="news-image">
                            <div class="image-wrapper">
                                <img src="{{ asset($content->image) }}" alt="Hình ảnh bài viết">
                                @if($content->note)
                                    <p>{{ $content->note }}</p>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Bên phải: Tin tức/Sản phẩm liên quan --}}
        <div class="news-detail-right">
            <h3>Tin tức liên quan</h3>
            <ul>
                @foreach($relatedNews as $related)
                <li style="margin-bottom: 20px; text-align: center; cursor: pointer;"
                    onclick="window.location='{{ route('chi-tiet-tin-tuc', $related->news_id) }}'">
                    @if($related->contents->first()?->image)
                        <img src="{{ asset($related->contents->first()->image) }}" alt="Hình liên quan" class="news-image-right">
                    @endif
                    <a>{{ Str::limit($related->title, 58) }}</a>
                    <p>{{ date('d/m/Y', strtotime($related->post_at)) }}</p>
                </li>
                @endforeach
            </ul>

            <h3>Sản phẩm liên quan</h3>
            <ul class="related-products-list">
                @foreach($relatedProducts ?? [] as $product)
                    <li class="related-product-item" onclick="window.location='{{ route('chi-tiet-san-pham', $product->product_id) }}'">
                        <div class="shop-info">
                            @if($product->store->user->image)
                                <img src="{{ asset('storage/' . $product->store->user->image) }}" alt="Ảnh chủ shop" class="shop-image">
                            @endif
                            <div class="shop-name">
                                <strong>{{ Str::limit($product->store->name ?? 'Không xác định', 38) }}</strong>
                            </div>
                        </div>

                        @if($product->images->first()?->image)
                            <img src="{{ asset('storage/' . $product->images->first()->image) }}" alt="Hình sản phẩm" class="product-image">
                        @endif

                        <a href="{{ route('chi-tiet-san-pham', $product->product_id) }}" class="product-name">
                            {{ Str::limit($product->name, 62) }}
                        </a>

                        <p class="product-price">{{ number_format($product->price,0,'','.') }}đ</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    
    <div class="newsdetail-btn">
        <a href="{{ route('tin-tuc') }}">
            <i class="fa-solid fa-arrow-left"></i> Danh sách tin tức
        </a>
    </div>
</div>
@endsection