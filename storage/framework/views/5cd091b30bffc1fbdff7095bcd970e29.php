<!DOCTYPE html>
<html lang="en">
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
            <a class="home-heading" href="<?php echo e(Auth::check() && Auth::user()->role != 1 ? route('admin') : route('trang-chu')); ?>" target="_top">
                <?php echo e(Auth::check() && Auth::user()->role != 1 ? 'QUẢN LÝ SÂN THỂ THAO' : 'TRANG CHỦ'); ?>

            </a>       
            <div class="header-login">
                <form action="<?php echo e(route('dang-xuat')); ?>" method="post" onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="signup-btn">Đăng xuất</button>
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
                            <i class="fa-solid fa-user-tie"></i>
                            <?php if(Auth::check()): ?>
                                <?php
                                    $user = Auth::user();
                                ?>
                                <a class="avatar-name" href="<?php echo e(route('thong-tin-tai-khoan')); ?>" target="_self">
                                    <?php echo e($user->username); ?>

                                </a>
                            <?php else: ?>
                                <a class="avatar-name" href="<?php echo e(route('dang-nhap')); ?>" target="_self">
                                    Đăng Nhập
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="admin-manage">
                            <li>
                                <a href="<?php echo e(route('thong-tin-tai-khoan')); ?>">Lịch sử đặt sân</a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('thong-tin-ca-nhan')); ?>">Thông tin cá nhân</a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('thay-doi-mat-khau')); ?>">Thay đổi mật khẩu</a>
                            </li>
                            <li>
                                <a href="#" onclick="event.preventDefault(); handleAccountDelete();">Xóa tài khoản</a>
                            </li>

                            <form id="delete-account-form" action="<?php echo e(route('xoa-tai-khoan')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="password" id="delete-password">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="admin">
                    <div class="admin-section-right"> 
                        <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!-- End: Content -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Designed by Group 48</p>
        </div>
        <!-- End: Footer -->
    </div>

    <!-- Lightbox hiển thị ảnh lớn -->
    <div id="image-popup" onclick="hideImage()" style="
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.7);
        justify-content: center;
        align-items: center;
    ">
        <img id="popup-img" src="" style="max-width:90%; max-height:90%; box-shadow: 0 0 10px #000;">
    </div>
    <script>
        function showImage(src) {
            document.getElementById('popup-img').src = src;
            document.getElementById('image-popup').style.display = 'flex';
        }

        function hideImage() {
            document.getElementById('image-popup').style.display = 'none';
        }

        function handleAccountDelete() {
            if (confirm('Bạn có chắc chắn muốn xóa tài khoản này không?')) {
                const password = prompt('Vui lòng nhập mật khẩu để xác nhận:');
                if (password) {
                    document.getElementById('delete-password').value = password;
                    document.getElementById('delete-account-form').submit();
                } else {
                    alert('Bạn chưa nhập mật khẩu. Hủy thao tác xóa tài khoản.');
                }
            }
        }
    </script>
</body>
</html>
<?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/layouts/client/account.blade.php ENDPATH**/ ?>