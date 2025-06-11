<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đặt sân thể thao - <?php echo $__env->yieldContent('title'); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('fonts/fontawesome-free-6.5.2/css/all.min.css')); ?>">
</head>
<body>
    <div id="main">

        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="
                <?php if(Auth::check() && Auth::user()->role != 1): ?>
                    <?php echo e(route('admin')); ?>

                <?php else: ?>
                    <?php echo e(route('trang-chu')); ?>

                <?php endif; ?>
            " target="_top">Đặt sân thể thao</a>            
            <div class="header-login">
                <form action="<?php echo e(route('dang-xuat')); ?>" method="post" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất?');">
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
<<<<<<< HEAD
                            <i class="avatar fa-solid fa-user-tie"></i>
=======
                            <i class="fa-solid fa-user-tie"></i>
>>>>>>> 80d6e7c (Cập nhật giao diện)
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
                                <a href="<?php echo e(route('thong-tin-tai-khoan')); ?>" target="">Lịch sử đặt sân</a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('thong-tin-ca-nhan')); ?>" target="">Thông tin cá nhân</a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('thay-doi-mat-khau')); ?>" target="">Thay đổi mật khẩu</a>
                            </li>
                            <li>
                                <a href="#" onclick="event.preventDefault(); handleAccountDelete();">Xóa tài khoản</a>
                            </li>

                            <!-- Form ẩn -->
                            <form id="delete-account-form" action="<?php echo e(route('xoa-tai-khoan')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="password" id="delete-password">
                            </form>
                        </div>
                    </div>
                </div>

                <div class="admin">
                    <div class="admin-section-right"> 
                        <?php echo $__env->yieldContent('content'); ?> <!-- Phần nội dung chính sẽ được hiển thị ở đây -->
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
        // Hàm hiển thị ảnh trong popup
        function showImage(src) {
            document.getElementById('popup-img').src = src;
            document.getElementById('image-popup').style.display = 'flex';
        }

        // Hàm ẩn popup khi click vào vùng tối
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