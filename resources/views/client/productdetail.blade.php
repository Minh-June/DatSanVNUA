@extends('layouts.client.client')

@section('title', $product->name ?? 'Trang Thông tin sản phẩm')

@section('css')
<link rel="stylesheet" href="{{ asset('css/product-detail.css') }}">
@endsection

@section('content')
<div id="content" class="order-section">

    <h2 class="order-heading">THÔNG TIN SẢN PHẨM</h2>

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif
    
    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <!-- Chi tiết sản phẩm -->
    <div class="product-detail-wrapper">

        <!-- Bên trái: Ảnh sản phẩm -->
        <div class="product-detail-left">
            @if($product->images && $product->images->count() > 0)
                <div class="main-image slider-track-wrapper">
                    <div class="slider-track">
                        @foreach($product->images as $img)
                            <img src="{{ asset('storage/' . $img->image) }}" 
                                alt="{{ $product->name }}" 
                                class="football-img">
                        @endforeach
                    </div>

                    <button class="main-arrow left"><i class="fa-solid fa-chevron-left"></i></button>
                    <button class="main-arrow right"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            @else
                <img src="{{ asset('images/no-image.png') }}" alt="Không có ảnh">
            @endif

            <!-- Ảnh phụ -->
            @if($product->images && $product->images->count() > 1)
                <div class="sub-images-wrapper">
                    <div class="sub-images" id="subImagesContainer">
                        @foreach($product->images as $img)
                            <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $product->name }}">
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Bên phải: Thông tin sản phẩm -->
        <div class="product-detail-right">
            <p class="product-name-right">{{ $product->name }}</p>
            @php
                $sizePrices = $product->sizes->pluck('price')->toArray();
                $minPrice = count($sizePrices) ? min($sizePrices) : $product->price;
                $maxPrice = count($sizePrices) ? max($sizePrices) : $product->price;
                @endphp

                <p class="product-price" id="productPrice">
                    @if($minPrice != $maxPrice)
                        {{ number_format($minPrice,0,',','.') }}đ - {{ number_format($maxPrice,0,',','.') }}đ
                    @else
                        {{ number_format($minPrice,0,',','.') }}đ
                    @endif
                </p>         
            <p class="product-description">{!! nl2br(e($product->description)) !!}</p>

            @if($product->sizes && $product->sizes->count() > 0)
                <p class="product-sizes">Size:
                @foreach($product->sizes as $size)
                    <span class="size-item"
                        onclick="selectSize(this, {{ $size->product_size_id }}, {{ $size->quantity }}, {{ $size->price }})">
                        {{ $size->name }}
                    </span>
                @endforeach
                </p>
            @endif

            <!-- Số lượng -->
            <div class="product-quantity">
                <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                <input type="text" id="quantityInput" value="1" readonly>
                <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>

                <form id="addToCartForm" method="POST" action="{{ route('luu-san-pham-productdetail') }}" onsubmit="return checkSizeSelected()"> 
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <input type="hidden" name="store_id" value="{{ $product->store_id }}">
                    <input type="hidden" name="name" value="{{ $product->name }}">
                    <input type="hidden" name="price" id="hiddenPrice" value="{{ $product->price }}">
                    <input type="hidden" name="image" value="{{ $product->images->first()->image ?? '' }}">
                    <input type="hidden" name="quantity" id="hiddenQuantity" value="1">
                    
                    @if($product->sizes && $product->sizes->count() > 0)
                        <input type="hidden" name="product_size_id" id="selectedSizeId" value="">
                    @else
                        <input type="hidden" name="product_size_id" value="">
                    @endif

                    <button type="submit" class="store-add-btn" style="margin-left:30px;">Thêm vào giỏ hàng</button>
                </form>

                <a href="#" class="buy-now-btn" style="margin-left:30px; font-size:17px;" 
                    onclick="event.preventDefault(); buyNow();">Mua ngay</a>
            </div>
        </div>
    </div>

    <h2 class="order-heading">Sản phẩm liên quan</h2>
    
    @if($similarProducts && $similarProducts->count() > 0)
        <div class="store-list-container" style="min-height: 550px; margin-top:40px;">
            <div class="store-card">
                <!-- Header -->
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
                        <p class="store-owner">{{ $store->user ? $store->user->fullname : 'Chưa xác định' }}</p>
                    </div>
                </div>

                <!-- Gallery sản phẩm tương tự -->
                <div class="store-products-gallery">
                    @foreach($similarProducts as $simProduct)
                        @php
                            $simImg = $productImages[$simProduct->name] ?? ($simProduct->images->first()->image ?? null);
                        @endphp
                        <div class="product-item" onclick="location.href='{{ route('chi-tiet-san-pham', $simProduct->product_id) }}'">
                            <img src="{{ $simImg ? asset('storage/' . $simImg) : asset('images/no-image.png') }}" alt="{{ $simProduct->name }}">
                            <div class="product-info">
                                <p class="product-name">{{ $simProduct->name }}</p>
                                <p class="product-price">
                                    {{ number_format($simProduct->sizes->min('price') ?? $simProduct->price, 0, ',', '.') }}đ
                                </p>
                                
                                <!-- Form POST thêm vào giỏ hàng -->
                                <form method="POST" action="{{ route('luu-san-pham-storesboard') }}">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $simProduct->product_id }}">
                                    <input type="hidden" name="store_id" value="{{ $simProduct->store_id }}">
                                    <input type="hidden" name="name" value="{{ $simProduct->name }}">
                                    <input type="hidden" name="price" value="{{ $simProduct->price }}">
                                    <input type="hidden" name="image" value="{{ $simImg }}">
                                    <input type="hidden" name="quantity" value="1">

                                    @if($simProduct->sizes && $simProduct->sizes->count() > 0)
                                        <input type="hidden" name="size" value="{{ $simProduct->sizes->first()->name }}">
                                        <input type="hidden" name="product_size_id" value="{{ $simProduct->sizes->first()->product_size_id }}">
                                    @else
                                        <input type="hidden" name="size" value="">
                                        <input type="hidden" name="product_size_id" value="">
                                    @endif

                                    <button type="submit" class="store-add-btn">Thêm vào giỏ hàng</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="newsdetail-btn" style="margin-bottom:60px;">
        <a href="{{ route('chi-tiet-cua-hang', $store->store_id) }}">
            <i class="fa-solid fa-arrow-left"></i> Quay lại cửa hàng
        </a>
    </div>
</div>

<script>
    function buyNow() {
        // Kiểm tra size nếu có
        @if($product->sizes && $product->sizes->count() > 0)
            if (!selectedSpan) {
                alert('Vui lòng chọn size sản phẩm trước!');
                return;
            }
        @endif

        let form = document.getElementById('addToCartForm');
        let formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => {
            // Hiện alert thành công
            alert('Thêm sản phẩm vào giỏ hàng thành công!');
            // Chuyển hướng sang giỏ hàng
            window.location.href = "{{ route('gio-hang') }}";
        })
        .catch(error => {
            console.error('Lỗi khi mua ngay:', error);
            alert('Thêm sản phẩm thất bại!');
        });
    }

    // ===== Chọn size và product_size_id, cập nhật giá =====
    let selectedSpan = null;
    let maxQty = 1; // tồn kho size

    function selectSize(span, sizeId, sizeQty, sizePrice) {
        // Nếu click lại size đã chọn → bỏ chọn
        if (selectedSpan === span) {
            span.classList.remove('selected');
            selectedSpan = null;

            // Reset input hidden
            document.getElementById('selectedSizeId').value = '';

            // Trả về giá min-max
            document.getElementById('hiddenPrice').value = '';
            document.getElementById('productPrice').innerText = "{{ number_format($minPrice,0,',','.') }}" 
                + ({{ $minPrice }} != {{ $maxPrice }} ? ' - {{ number_format($maxPrice,0,',','.') }}đ' : 'đ');

            // Reset maxQty và số lượng
            maxQty = 1;
            document.getElementById('quantityInput').value = 1;
            document.getElementById('hiddenQuantity').value = 1;
            return;
        }

        // Bỏ highlight size trước đó nếu có
        if (selectedSpan) selectedSpan.classList.remove('selected');
        span.classList.add('selected');
        selectedSpan = span;

        // Gán sizeId vào input hidden
        document.getElementById('selectedSizeId').value = sizeId;

        // Cập nhật maxQty theo size
        maxQty = sizeQty;

        // Reset quantity khi chọn size mới
        document.getElementById('quantityInput').value = 1;
        document.getElementById('hiddenQuantity').value = 1;

        // Cập nhật giá hiển thị và hiddenPrice theo size
        document.getElementById('productPrice').innerText = sizePrice.toLocaleString('vi-VN') + 'đ';
        document.getElementById('hiddenPrice').value = sizePrice;
    }

    // ===== Check size khi thêm giỏ =====
    function checkSizeSelected() {
        const sizeInput = document.getElementById('selectedSizeId');
        @if($product->sizes && $product->sizes->count() > 0)
            if (sizeInput && sizeInput.value === '') {
                alert('Vui lòng chọn size sản phẩm trước khi thêm vào giỏ hàng!');
                return false;
            }
        @endif
        return true;
    }

    // ===== Số lượng (phụ thuộc size) =====
    function changeQuantity(delta) {
        // Nếu sản phẩm có size nhưng chưa chọn size → báo alert
        @if($product->sizes && $product->sizes->count() > 0)
            if (!selectedSpan) {
                alert('Vui lòng chọn size sản phẩm trước !');
                return; // dừng hàm
            }
        @endif

        const qtyInput = document.getElementById('quantityInput');
        const hiddenQty = document.getElementById('hiddenQuantity');

        let qty = parseInt(qtyInput.value) || 1;
        qty += delta;

        if (qty < 1) qty = 1;
        if (qty > maxQty) {
            qty = maxQty;
            alert('Số lượng không được vượt quá tồn kho của size đã chọn!');
        }

        qtyInput.value = qty;
        if (hiddenQty) hiddenQty.value = qty;
    }

    // Trượt slider ảnh chính 
    const track = document.querySelector('.slider-track');
    const mainImages = @json($product->images->pluck('image'));
    let currentIndex = 0;

    // Ảnh phụ
    const subImagesContainer = document.getElementById('subImagesContainer');
    const visibleSubCount = 4; // số ảnh phụ hiển thị

    // Hàm đổi ảnh chính
    function changeMainImage(delta) {
        if (!mainImages.length) return;

        currentIndex += delta;

        if (currentIndex < 0) currentIndex = mainImages.length - 1;
        if (currentIndex >= mainImages.length) currentIndex = 0;

        const wrapper = document.querySelector('.slider-track-wrapper');
        const width = wrapper.offsetWidth; // width của container
        track.style.transform = `translateX(-${currentIndex * width}px)`; // trượt mượt ảnh chính

        updateSubImages();
    }

    // Auto slide 5s
    let autoSlide = setInterval(() => changeMainImage(1), 3000);

    // Nút trái/phải
    const btnLeft = document.querySelector('.main-arrow.left');
    const btnRight = document.querySelector('.main-arrow.right');
    if (btnLeft && btnRight) {
        btnLeft.addEventListener('click', () => { 
            clearInterval(autoSlide); 
            changeMainImage(-1); 
        });
        btnRight.addEventListener('click', () => { 
            clearInterval(autoSlide); 
            changeMainImage(1); 
        });
    }

    // Pause khi hover main-image
    const container = document.querySelector('.slider-track-wrapper');
    if (container) {
        container.addEventListener('mouseenter', () => clearInterval(autoSlide));
        container.addEventListener('mouseleave', () => {
            autoSlide = setInterval(() => changeMainImage(1), 3000);
        });
    }

    // Cập nhật sub-images active và trượt sub-images khi cần
    function updateSubImages() {
        if (!subImagesContainer) return;
        const imgs = subImagesContainer.querySelectorAll('img');
        if (!imgs.length) return;

        const imgWidth = imgs[0].offsetWidth + 17; // width + gap
        let shift = 0;

        imgs.forEach((img, idx) => {
            img.classList.toggle('active', idx === currentIndex);
            img.onclick = () => {
                currentIndex = idx;
                changeMainImage(0);
            };
        });

        // Trượt sub-images nếu ảnh active vượt quá số ảnh hiển thị
        if (currentIndex >= visibleSubCount) {
            shift = (currentIndex - visibleSubCount + 1) * imgWidth;
        }
        subImagesContainer.style.transform = `translateX(-${shift}px)`;
    }

    // Khởi tạo
    updateSubImages();

    // Optional: update lại track width khi resize
    window.addEventListener('resize', () => {
        const wrapper = document.querySelector('.slider-track-wrapper');
        const width = wrapper.offsetWidth;
        track.style.transform = `translateX(-${currentIndex * width}px)`;
    });
</script>

@endsection
