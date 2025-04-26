<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đặt lịch sân thể thao - <?php echo $__env->yieldContent('title'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('fonts/fontawesome-free-6.5.2/css/all.min.css')); ?>">
</head>
<body>
    <div id="main">

        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="<?php echo e(route('trang-chu')); ?>" target="_top">Đặt lịch sân thể thao</a>
            
            <div class="header-login">
                <i class="avatar fa-solid fa-user-tie"></i>
                <?php if(Auth::check()): ?>
                    <a class="signup-btn" href="<?php echo e(route('thong-tin-tai-khoan')); ?>" target="_self">
                        <?php echo e(Auth::user()->username); ?>

                    </a>
                <?php else: ?>
                    <a class="signup-btn" href="<?php echo e(route('dang-nhap')); ?>" target="_self">Đăng Nhập</a>
                <?php endif; ?>
            </div>
        </div>
        <!-- End: Header -->

        <?php echo $__env->yieldContent('content'); ?> <!-- Nơi để nội dung của các trang khác được chèn vào -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Powered by MJ</p>
        </div>
        <!-- End: Footer -->

    </div>
</body>
</html>
<?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/layouts/client/client.blade.php ENDPATH**/ ?>