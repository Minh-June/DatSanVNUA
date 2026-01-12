@extends('layouts.client.client')

@section('title', 'Tin tức thể thao')

@section('content')
<div id="content" class="order-section">
    <h2 class="order-heading">TIN TỨC THỂ THAO</h2>

    <form method="GET" action="{{ route('tin-tuc') }}">
        <div class="news-method">
            <div class="news-filter">
                <label for="newsType"">Loại tin tức:</label>
                <select id="newsType" name="news_type_id">
                    <option value="">Tất cả</option>
                    @foreach($newsTypes as $type)
                        <option value="{{ $type->news_type_id }}" {{ request('news_type_id') == $type->news_type_id ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="order-football-btn" style="font-size: 18px;">Tìm kiếm</button>
            </div>

            <div class="news-search">
                <label for="searchTitle">Tìm kiếm:</label>
                <input type="text" id="searchTitle" name="title" placeholder="Nhập tiêu đề bài viết..." 
                    value="{{ request('title') }}">
                <button type="submit" class="order-football-btn" style="font-size: 18px;">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <div class="news-list">
        @forelse($newsList as $news)
            @php
                $firstImage = $news->contents->first()?->image;
                $newsUrl = route('chi-tiet-tin-tuc', $news->news_id);
            @endphp
            <div class="news-item" onclick="window.location='{{ $newsUrl }}'" style="cursor:pointer;">
                <div class="news-thumb">
                    @if($firstImage)
                        <img src="{{ asset($firstImage) }}" alt="{{ $news->title }}">
                    @else
                        <img src="{{ asset('images/no-image.png') }}" alt="Không có ảnh">
                    @endif
                </div>

                <div class="news-info">
                    <h3 class="news-title">{{ $news->title }}</h3>
                    <p class="news-meta">
                        <span>Loại tin tức: {{ $news->type->name }}</span> |
                        <span>Người đăng: {{ $news->user->fullname ?? 'Ẩn danh' }}</span> |
                        <span>Ngày: {{ date('d/m/Y', strtotime($news->post_at)) }}</span>
                    </p>
                    <p class="news-content">{!! strip_tags($news->contents->first()?->content ?? '') !!}</p>
                </div>
            </div>
        @empty
            <p style="text-align:center; margin:169px 0;">Hiện chưa có bài đăng nào</p>
        @endforelse
    </div>

    {{-- Pagination custom --}}
    <ul class="pagination">
        {{-- Previous --}}
        <li class="pagination-item {{ $newsList->onFirstPage() ? 'disabled' : '' }}">
            <a href="{{ $newsList->previousPageUrl() ?? '#' }}" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-left"></i>
            </a>
        </li>

        {{-- Page numbers --}}
        @for ($i = 1; $i <= $newsList->lastPage(); $i++)
            @if ($i <= 5 || $i == $newsList->lastPage())
                <li class="pagination-item {{ $newsList->currentPage() == $i ? 'pagination-item--active' : '' }}">
                    <a href="{{ $newsList->url($i) }}" class="pagination-item__link">{{ $i }}</a>
                </li>
                @if ($i == 5 && $newsList->lastPage() > 6)
                    <li class="pagination-item"><span class="pagination-item__link">...</span></li>
                @endif
            @endif
        @endfor

        {{-- Next --}}
        <li class="pagination-item {{ $newsList->hasMorePages() ? '' : 'disabled' }}">
            <a href="{{ $newsList->nextPageUrl() ?? '#' }}" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-right"></i>
            </a>
        </li>
    </ul>

</div>
@endsection
