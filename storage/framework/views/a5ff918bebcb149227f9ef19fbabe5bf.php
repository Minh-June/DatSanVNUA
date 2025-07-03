<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ĐẶT SÂN VNUA</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('fonts/fontawesome-free-6.5.2/css/all.min.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
    <div id="main">
        <!-- Begin: Header -->
        <div id="header">
            <ul id="nav">
                <li>
                    <a class="home-heading" href="<?php echo e(route('dang-nhap')); ?>" target="_top">
                        <i class="fa-solid fa-house"></i>TRANG CHỦ
                    </a>
                </li>
                <li>
                    <a class="home-heading search-btn" href="<?php echo e(route('dang-nhap')); ?>" onclick="alert('Vui lòng đăng nhập để sử dụng chức năng !');">
                        <i class="fa-solid fa-magnifying-glass"></i>TÌM KIẾM
                    </a>
                </li>
            </ul>
            <!-- End: Nav -->

            <div class="header-login">
                <a class="login-btn dash" href="<?php echo e(route('dang-nhap')); ?>">Đăng Nhập</a>
                <a class="signup-btn" href="<?php echo e(route('dang-ky')); ?>">Đăng Ký</a>
            </div>
        </div>
        <!-- End: Header -->

        <div id="slider">
            <div class="slider-track">
                <img src="<?php echo e(asset('image/slider/slider1.jpg')); ?>" alt="">
                <img src="<?php echo e(asset('image/slider/slider2.jpg')); ?>" alt="">
                <img src="<?php echo e(asset('image/slider/slider3.jpg')); ?>" alt="">
                <img src="<?php echo e(asset('image/slider/slider4.jpg')); ?>" alt="">
                <img src="<?php echo e(asset('image/slider/slider1.jpg')); ?>" alt=""> <!-- Ảnh đầu được nhân bản để tạo hiệu ứng lặp -->
            </div>
            <button class="slider-btn-left"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="slider-btn-right"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
        
        <!-- Begin: Content -->
        <?php $__currentLoopData = $groupedYards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeName => $yards): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="content" class="content-section">
                <h2 class="content-heading">
                    <?php echo e($typeName); ?>

                </h2>
                <div class="content-list">
                    <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                <p class="content-body-name">
                                    <?php echo e($yard->name); ?>

                                </p>
                                <a href="<?php echo e(route('dang-nhap')); ?>"
                                onclick="alert('Vui lòng đăng nhập để sử dụng chức năng !');"
                                class="order-football-btn">
                                    Chọn sân
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="clear"></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <h3>GIỜ MỞ CỬA</h3>
                        <p>Thứ Hai đến thứ Sáu:</p>
                        <p>Từ 8:00 sáng đến 17:00 chiều</p>
                    </div>
                    <div class="contact-hour">
                        <h3>MẠNG XÃ HỘI</h3>
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

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Designed by Group 48</p>
        </div>
        <!-- End: Footer -->
         
    </div>

    <script>
        // Trượt slider
        const track = document.querySelector('.slider-track');
        const slides = document.querySelectorAll('.slider-track img'); // Tất cả ảnh
        const totalSlides = slides.length; // Tổng số ảnh (cả ảnh clone)
        let currentIndex = 0; // Vị trí hiện tại

        // Trượt tự động mỗi 3 giây
        let autoSlide = setInterval(() => {
            currentIndex++; // Tăng vị trí
            track.style.transition = 'transform 0.5s ease-in-out';
            track.style.transform = `translateX(-${currentIndex * 100}%)`; // Trượt sang trái

            // Nếu đang ở ảnh clone (cuối)
            if (currentIndex === totalSlides - 1) {
                setTimeout(() => {
                    track.style.transition = 'none';              // Tắt hiệu ứng
                    track.style.transform = 'translateX(0%)';     // Về ảnh đầu
                    currentIndex = 0;
                }, 500); // Khớp với thời gian transition
            }
        }, 3000);

        // Ấn nút chuyển slider
        const btnLeft = document.querySelector('.slider-btn-left');
        const btnRight = document.querySelector('.slider-btn-right');

        if (btnLeft && btnRight) {
            // Nút sang phải
            btnRight.addEventListener('click', () => {
                clearInterval(autoSlide); // Dừng auto
                currentIndex++;
                track.style.transition = 'transform 0.5s ease-in-out';
                track.style.transform = `translateX(-${currentIndex * 100}%)`;

                if (currentIndex === totalSlides - 1) {
                    setTimeout(() => {
                        track.style.transition = 'none';
                        track.style.transform = 'translateX(0%)';
                        currentIndex = 0;
                    }, 500);
                }
            });

            // Nút sang trái
            btnLeft.addEventListener('click', () => {
                clearInterval(autoSlide); // Dừng auto

                if (currentIndex === 0) {
                    // Nhảy về ảnh cuối thật (trước ảnh clone)
                    currentIndex = totalSlides - 2;
                    track.style.transition = 'none';
                    track.style.transform = `translateX(-${(currentIndex + 1) * 100}%)`;
                    setTimeout(() => {
                        track.style.transition = 'transform 0.5s ease-in-out';
                        track.style.transform = `translateX(-${currentIndex * 100}%)`;
                    }, 20);
                } else {
                    currentIndex--;
                    track.style.transition = 'transform 0.5s ease-in-out';
                    track.style.transform = `translateX(-${currentIndex * 100}%)`;
                }
            });
        }
    </script>
</body>
</html>
<?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/view.blade.php ENDPATH**/ ?>