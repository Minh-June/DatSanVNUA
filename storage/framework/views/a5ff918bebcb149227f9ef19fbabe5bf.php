<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đặt sân thể thao</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('fonts/fontawesome-free-6.5.2/css/all.min.css')); ?>">
</head>
<body>
    <div id="main">

        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="#" target="_top">Đặt sân thể thao</a>
            
            <div class="header-login">
                <a class="login-btn dash" href="<?php echo e(route('dang-nhap')); ?>">Đăng Nhập</a>
                <a class="signup-btn" href="<?php echo e(route('dang-ky')); ?>">Đăng Ký</a>
            </div>
        </div>
        <!-- End: Header -->

        <div id="slider">
<<<<<<< HEAD
            <img src="<?php echo e(asset('./image/slider/slider1.jpg')); ?>" alt="Slider Image" style="width: 100%; height: auto;">
=======
            <img src="<?php echo e(asset('./image/slider/slider1.jpg')); ?>" alt="Slider Image" style="max-width: 100%; max-height: 50%;">
>>>>>>> 80d6e7c (Cập nhật giao diện)
        </div>
        
        <!-- Begin: Content -->
        <?php $__currentLoopData = $groupedYards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeName => $yards): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="content" class="content-section">
<<<<<<< HEAD
                <h2 class="content-heading">
                    <?php echo e($typeName); ?>

                </h2>
=======
                <p class="content-heading">
                    <?php echo e($typeName); ?>

                </p>
>>>>>>> 80d6e7c (Cập nhật giao diện)
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
<<<<<<< HEAD
                                <h3 class="content-body-name">
                                    <?php echo e($yard->name); ?>

                                </h3>
                                <a class="order-football-btn" 
                                href="<?php echo e(route('dang-nhap')); ?>" 
                                onclick="alert('Vui lòng đăng nhập để đặt sân')">
                                Chọn sân
=======
                                <p class="content-body-name">
                                    <?php echo e($yard->name); ?>

                                </p>
                                <a href="<?php echo e(route('dang-nhap')); ?>"
                                onclick="alert('Vui lòng đăng nhập để đặt sân');"
                                class="order-football-btn">
                                    Chọn sân
>>>>>>> 80d6e7c (Cập nhật giao diện)
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<<<<<<< HEAD
                    <div class="clear"></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
=======
                </div>
            </div>
            <div class="clear"></div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
>>>>>>> 80d6e7c (Cập nhật giao diện)
        <!-- End: Content -->

        <!-- Begin: Contact section -->
        <div id="contact" class="content-section">
            <h2 class="content-heading">LIÊN HỆ</h2>

            <div class="row contact-content">
                <div class="col col-half contact-infor">
<<<<<<< HEAD
                    <p><i class="fa-solid fa-location-dot"></i>Hà Nội, Việt Nam</p>
                    <p><i class="fa-solid fa-phone"></i>Điện thoại: <a href="tel:+00 151515">+84 356645445</a></p>
                    <p><i class="fa-solid fa-envelope"></i>Email: <a href="mailto:mail@mail.com">minhjune18@gmail.com</a></p>
=======
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
>>>>>>> 80d6e7c (Cập nhật giao diện)
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
<<<<<<< HEAD
<<<<<<<< HEAD:storage/framework/views/a5ff918bebcb149227f9ef19fbabe5bf.php
<?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/view.blade.php ENDPATH**/ ?>
========
<?php /**PATH D:\laragon\www\qldatsan\resources\views/view.blade.php ENDPATH**/ ?>
>>>>>>>> 80d6e7c (Cập nhật giao diện):storage/framework/views/686831f9cf9cfe343b6b894f9d79d771.php
=======
<?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/view.blade.php ENDPATH**/ ?>
>>>>>>> 80d6e7c (Cập nhật giao diện)
