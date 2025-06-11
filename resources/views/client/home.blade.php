@extends('layouts.client.client')

@section('title', 'Trang chá»§')

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
                                <a href="{{ route('dat-san', ['yard_id' => $yard->yard_id]) }}" class="order-football-btn">Chá»n sĂ¢n</a>
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
            <h2 class="content-heading">LIĂN Há»†</h2>

            <div class="row contact-content">
                <div class="col col-half contact-infor">
                    <div class="contact-infor-header">  
                        <img src="/image/logo.png" alt="Logo máº·c Ä‘á»‹nh">
                        <div class="contact-infor-text">  
                            <h3>Há»ŒC VIá»†N NĂ”NG NGHIá»†P VIá»†T NAM</h3>
                            <h5>VIETNAM NATIONAL UNIVERSITY OF AGRICULTURE</h5>
                        </div>
                    </div>
                    <p><i class="fa-solid fa-location-dot"></i>TrĂ¢u Quá»³, Gia LĂ¢m, HĂ  Ná»™i, Viá»‡t Nam</p>
                    <p><i class="fa-solid fa-phone"></i>Äiá»‡n thoáº¡i: 84.024.62617586</p>
                    <p><i class="fa-solid fa-envelope"></i>Email: webmaster@vnua.edu.vn</p>
                </div>

                <div class="col col-half contact-form">
                    <form action="">
                        <div class="row">
                            <div class="col col-half">
                                <input type="text" name="" placeholder="TĂªn" required id="" class="form-control">
                            </div>
                            <div class="col col-half s-mt-8">
                                <input type="email" name="" placeholder="Email" required id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-8">
                            <div class="col col-full">
                                <input type="text" name="" placeholder="Ghi chĂº" required id="" class="form-control">
                            </div>
                        </div>
                        <input class="contact-btn pull-right mt-16" type="submit" value="Gá»­i">
                    </form>

                </div>
                
            </div>
        </div>
        <!-- End: Contact section -->
@endsection
