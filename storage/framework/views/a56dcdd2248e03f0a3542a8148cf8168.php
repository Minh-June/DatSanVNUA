<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Äáº·t sĂ¢n thá»ƒ thao - <?php echo $__env->yieldContent('title'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('fonts/fontawesome-free-6.5.2/css/all.min.css')); ?>">
</head>
<body>
    <div id="main">

        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="<?php echo e(route('trang-chu')); ?>" target="_top">Äáº·t sĂ¢n thá»ƒ thao</a>
            
            <div class="header-login">
                <form action="<?php echo e(route('dang-xuat')); ?>" method="post" style="display:inline;" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c cháº¯n muá»‘n Ä‘Äƒng xuáº¥t?');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="signup-btn">ÄÄƒng xuáº¥t</button>
                </form>
            </div>
        </div>
        <!-- End: Header -->

        <!-- Begin: Content -->
        <div id="content" class="admin-section">
            <div class="admin-content">
                <div class="admin">
                    <div class="admin-section-left">
                        <div class="header-section-left">
                            <i class="avatar fa-solid fa-user-tie"></i>
                            <?php if(Auth::check()): ?>
                                <a class="avatar-name" href="<?php echo e(route('thong-tin-tai-khoan')); ?>" target="_self">
                                    <?php echo e(Auth::user()->username); ?>

                                </a>
                            <?php else: ?>
                                <a class="avatar-name" href="<?php echo e(route('dang-nhap')); ?>" target="_self">ÄÄƒng Nháº­p</a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="admin-manage">
                            <li>
                                <ul class="section-left">
                                    <li><a href="<?php echo e(route('thong-tin-tai-khoan')); ?>" target="">Lá»‹ch sá»­ Ä‘áº·t sĂ¢n</a></li>
                                    <li><a href="<?php echo e(route('thong-tin-ca-nhan')); ?>" target="">ThĂ´ng tin cĂ¡ nhĂ¢n</a></li>
                                    <li><a href="<?php echo e(route('thay-doi-mat-khau')); ?>" target="">Thay Ä‘á»•i máº­t kháº©u</a></li>
                                </ul>
                            </li>
                        </div>  
                    </div>
                </div>

                <div class="admin">
                    <div class="admin-section-right"> 
                        <?php echo $__env->yieldContent('content'); ?> <!-- Pháº§n ná»™i dung chĂ­nh sáº½ Ä‘Æ°á»£c hiá»ƒn thá»‹ á»Ÿ Ä‘Ă¢y -->
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!-- End: Content -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Powered by Group 48</p>
        </div>
        <!-- End: Footer -->

    </div>
</body>
</html>
<?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/layouts/client/user.blade.php ENDPATH**/ ?>
