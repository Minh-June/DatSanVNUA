<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Äáº·t sĂ¢n thá»ƒ thao</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('fonts/fontawesome-free-6.5.2/css/all.min.css')); ?>">
</head>
<body>
    <div id="main">

        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="#" target="_top">Äáº·t sĂ¢n thá»ƒ thao</a>
            
            <div class="header-login">
                <a class="login-btn dash" href="<?php echo e(route('dang-nhap')); ?>">ÄÄƒng Nháº­p</a>
                <a class="signup-btn" href="<?php echo e(route('dang-ky')); ?>">ÄÄƒng KĂ½</a>
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
                                // Láº¥y hĂ¬nh áº£nh tá»« máº£ng $yard->images theo yard_id
                                $imageData = $yard->images->first(); // Láº¥y hĂ¬nh áº£nh Ä‘áº§u tiĂªn náº¿u cĂ³
                                if ($imageData) {
                                    // Hiá»ƒn thá»‹ hĂ¬nh áº£nh dÆ°á»›i dáº¡ng base64
                                    echo '<img src="' . $imageData->url . '" alt="" class="football-img">'; // Sá»­ dá»¥ng phÆ°Æ¡ng thá»©c getUrlAttribute
                                } else {
                                    // Hiá»ƒn thá»‹ hĂ¬nh áº£nh máº·c Ä‘á»‹nh náº¿u khĂ´ng cĂ³ hĂ¬nh áº£nh
                                    echo '<img src="' . asset('image/football.jpg') . '" alt="" class="football-img">';
                                }
                            ?>
                            <div class="content-body">
                                <p class="content-body-name">
                                    <?php echo e($yard->name); ?>

                                </p>
                                <a href="<?php echo e(route('dang-nhap')); ?>"
                                onclick="alert('Vui lĂ²ng Ä‘Äƒng nháº­p Ä‘á»ƒ Ä‘áº·t sĂ¢n');"
                                class="order-football-btn">
                                    Chá»n sĂ¢n
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

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Powered by Group 48</p>
        </div>
        <!-- End: Footer -->
         
    </div>
</body>
</html>
<?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/view.blade.php ENDPATH**/ ?>
