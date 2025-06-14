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
                    <a class="home-heading" href="<?php echo e(route('trang-chu')); ?>" target="_top">
                        <i class="fa-solid fa-house"></i>TRANG CHỦ
                    </a>
                </li>
                <li></li>
            </ul>
            <!-- End: Nav -->

            <div class="header-login">
                <a class="login-btn dash" href="<?php echo e(route('dang-nhap')); ?>">Đăng Nhập</a>
                <a class="signup-btn" href="<?php echo e(route('dang-ky')); ?>">Đăng Ký</a>
            </div>
        </div>
        <!-- End: Header -->

        <div id="slider">
            <img src="<?php echo e(asset('./image/slider/slider1.jpg')); ?>" alt="Slider Image" style="max-width: 100%; max-height: 50%;">
        </div>
        
        <!-- Begin: Content -->
        <?php $__currentLoopData = $groupedYards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeName => $yards): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="content" class="content-section">
                <p class="content-heading">
                    <?php echo e($typeName); ?>

                </p>
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
                                onclick="alert('Vui lòng đăng nhập để đặt sân !');"
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
                </div>

                <div class="col col-half contact-form">
                    <form action="<?php echo e(route('dang-nhap')); ?>" method="GET">
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
            <p class="copyright">Designed by Group 48</p>
        </div>
        <!-- End: Footer -->
         
    </div>
        
</body>
</html>
<?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/view.blade.php ENDPATH**/ ?>