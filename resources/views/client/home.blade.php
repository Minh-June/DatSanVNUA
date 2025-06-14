@extends('layouts.client.client')

@section('title', 'Trang chủ')

@section('content')
        <div id="slider">
            <img src="{{ asset('image/slider/slider1.jpg') }}" alt="Slider Image" style="width: 100%; height: auto;">
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
                                <a href="{{ route('dat-san', ['yard_id' => $yard->yard_id]) }}" class="order-football-btn">Chọn sân</a>
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
                </div>

                <div class="col col-half contact-form">
                    <form action="">
                        <div class="row">
                            <div class="col col-half">
                                <input type="text" name="" placeholder="Tên" required id="" class="form-control">
                            </div>
                            <div class="col col-half s-mt-8">
                                <input type="email" name="" placeholder="Email" required id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-8">
                            <div class="col col-full">
                                <input type="text" name="" placeholder="Ghi chú" required id="" class="form-control">
                            </div>
                        </div>
                        <input class="contact-btn pull-right mt-16" type="submit" value="Gửi">
                    </form>

                </div>
                
            </div>
        </div>
        <!-- End: Contact section -->
@endsection
