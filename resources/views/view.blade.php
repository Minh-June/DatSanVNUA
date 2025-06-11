<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đặt sân thể thao</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('fonts/fontawesome-free-6.5.2/css/all.min.css') }}">
</head>
<body>
    <div id="main">

        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="#" target="_top">Đặt sân thể thao</a>
            
            <div class="header-login">
                <a class="login-btn dash" href="{{ route('dang-nhap') }}">Đăng Nhập</a>
                <a class="signup-btn" href="{{ route('dang-ky') }}">Đăng Ký</a>
            </div>
        </div>
        <!-- End: Header -->

        <div id="slider">
            <img src="{{ asset('./image/slider/slider1.jpg') }}" alt="Slider Image" style="max-width: 100%; max-height: 50%;">
        </div>
        
        <!-- Begin: Content -->
        @foreach ($groupedYards as $typeName => $yards)
            <div id="content" class="content-section">
<<<<<<< HEAD
                <h2 class="content-heading">
                    {{ $typeName }}
                </h2>
=======
                <p class="content-heading">
                    {{ $typeName }}
                </p>
>>>>>>> 80d6e7c (Cập nhật giao diện)
                <div class="content-list">
                    @foreach ($yards as $yard)
                        <div class="content-item">
                            <?php
                                // Lấy hình ảnh từ mảng $yard->images theo yard_id
                                $imageData = $yard->images->first(); // Lấy hình ảnh đầu tiên nếu có
                                if ($imageData) {
                                    // Hiển thị hình ảnh dưới dạng base64
                                    echo '<img src="' . $imageData->url . '" alt="" class="football-img">'; // Sử dụng phương thức getUrlAttribute
                                } else {
                                    // Hiển thị hình ảnh mặc định nếu không có hình ảnh
                                    echo '<img src="' . asset('image/football.jpg') . '" alt="" class="football-img">';
                                }
                            ?>
                            <div class="content-body">
<<<<<<< HEAD
                                <h3 class="content-body-name">
                                    {{ $yard->name }}
                                </h3>
                                <a class="order-football-btn" 
                                href="{{ route('dang-nhap') }}" 
                                onclick="alert('Vui lòng đăng nhập để đặt sân')">
                                Chọn sân
=======
                                <p class="content-body-name">
                                    {{ $yard->name }}
                                </p>
                                <a href="{{ route('dang-nhap') }}"
                                onclick="alert('Vui lòng đăng nhập để đặt sân');"
                                class="order-football-btn">
                                    Chọn sân
>>>>>>> 80d6e7c (Cập nhật giao diện)
                                </a>
                            </div>
                        </div>
                    @endforeach
<<<<<<< HEAD
                    <div class="clear"></div>
=======
>>>>>>> 80d6e7c (Cập nhật giao diện)
                </div>
            </div>
            <div class="clear"></div>
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
                            <h5>VIETNAM NATIONAL UNIVERSITY OF AGRICULTURE</h5>
                        </div>
                    </div>
                    <p><i class="fa-solid fa-location-dot"></i>Trâu Quỳ, Gia Lâm, Hà Nội, Việt Nam</p>
                    <p><i class="fa-solid fa-phone"></i>Điện thoại: 84.024.62617586</p>
                    <p><i class="fa-solid fa-envelope"></i>Email: webmaster@vnua.edu.vn</p>
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

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Powered by Group 48</p>
        </div>
        <!-- End: Footer -->
         
    </div>
</body>
</html>
