@extends('layouts.client.client')

@section('title', 'Trang chủ')

@section('content')
        @if(session('success'))
            <script>alert("{{ session('success') }}");</script>
        @endif  
        
        @if(session('error'))
            <script>alert("{{ session('error') }}");</script>
        @endif

        <div id="slider">
            <div class="slider-track">
                <img src="{{ asset('image/slider/slider1.jpg') }}" alt="">
                <img src="{{ asset('image/slider/slider2.jpg') }}" alt="">
                <img src="{{ asset('image/slider/slider3.jpg') }}" alt="">
                <img src="{{ asset('image/slider/slider4.jpg') }}" alt="">
                <img src="{{ asset('image/slider/slider1.jpg') }}" alt=""> <!-- Ảnh đầu được nhân bản để tạo hiệu ứng lặp -->
            </div>
            <button class="slider-btn-left"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="slider-btn-right"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        
        <!-- Begin: Content -->
        @foreach ($groupedYards as $typeName => $yards)
            <div id="content" class="content-section">
                <h2 class="content-heading">
                    {{ $typeName }}
                </h2>
                <div class="content-list">
                    @foreach ($yards as $yard)
                        <div class="content-item">
                            <img src="{{ $yard->first_image_url ?? asset('image/football.jpg') }}" alt="" class="football-img">
                            <div class="content-body">
                                <h3 class="content-body-name">
                                    {{ $yard->name }}
                                </h3>
                                <a href="{{ route('dat-san', ['yard_id' => $yard->yard_id, 'type_id' => $yard->type_id]) }}" class="order-football-btn">Chọn sân</a>
                            </div>
                        </div>
                    @endforeach
                    <div class="clear"></div>
                </div>
            </div>
        @endforeach
        <!-- End: Content -->
            
        <!-- Begin: Contact section -->
        <div id="contact" class="content-section">
            <h2 class="content-heading">LIÊN HỆ</h2>

            <div class="row contact-content">
                <div class="col col-half contact-infor">
                    <div class="contact-infor-header">  
                        <img src="/image/logo.png" alt="Logo mặc định">
                        <div class="contact-infor-text">  
                            <h3>HỌC VIỆN NÔNG NGHIỆP VIỆT NAM</h3>
                            <h4>TRUNG TÂM GIÁO DỤC THỂ CHẤT VÀ THỂ THAO</h4>
                        </div>
                    </div>
                    <p><i class="fa-solid fa-location-dot"></i>Trâu Quỳ, Gia Lâm, Hà Nội, Việt Nam</p>
                    <p>
                        <i class="fa-solid fa-phone"></i>
                        <span class="website-label">Điện thoại:</span>
                        <a href="tel:+8424362618401" class="website-link">024(3) 62.618.401</a>
                    </p>
                    <p>
                        <i class="fa-solid fa-envelope"></i>
                        <span class="website-label">Email:</span>
                        <a href="mailto:gdtc@vnua.edu.vn" class="website-link">gdtc@vnua.edu.vn</a>
                    </p>
                    <p>
                        <i class="fa-solid fa-globe"></i>
                        <span class="website-label">Website:</span>
                        <a href="http://gdtc.vnua.edu.vn" target="_blank" class="website-link">http://gdtc.vnua.edu.vn</a>
                    </p>
                    <div class="contact-hour">
                        <h4>GIỜ MỞ CỬA</h4>
                        <p>Thứ Hai đến thứ Sáu:</p>
                        <p>Từ 8:00 sáng đến 17:00 chiều</p>
                    </div>
                    <div class="contact-hour">
                        <h4>MẠNG XÃ HỘI</h4>
                        <a href="https://www.facebook.com/hocviennongnghiep" target="_blank" class="social-icon">
                            <i class="fa-brands fa-facebook"></i>
                        </a>
                        <a href="https://www.youtube.com/channel/UC_O9ofPYoZ_zYvWuE8ITMeg" target="_blank" class="social-icon">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col col-half contact-form">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3510.0410582775726!2d105.93110385769693!3d21.00503310840004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135a8cddd6b4f1d%3A0xdceafde72a78e64c!2zU8OibiB24bqtbiDEkeG7mW5nIEjhu41jIHZp4buHbiBOw7RuZyBOZ2hp4buHcCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1750306280343!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
        <!-- End: Contact section -->
@endsection
