<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ĐẶT SÂN VNUA</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/responsive.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('fonts/fontawesome-free-6.5.2/css/all.min.css')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <div id="main">
        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="<?php echo e(route('thong-ke-bao-cao')); ?>" target="_self">TRUNG TÂM QUẢN LÝ</a>
            
            <div class="header-login">
                <form action="<?php echo e(route('dang-xuat')); ?>" method="post" onsubmit="return confirm('Bạn có chắc chắn muốn đăng xuất ?');">
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
                            <?php if(Auth::check()): ?>
                                <?php if(Auth::user()->image): ?>
                                    <img src="<?php echo e(asset('storage/' . Auth::user()->image)); ?>" alt="Avatar" class="user-avatar-admin">
                                <?php else: ?>
                                    <i class="avatar fa-solid fa-user-tie"></i>
                                <?php endif; ?>
                                <a class="avatar-name" href="<?php echo e(route('thong-tin-tai-khoan')); ?>">
                                    <?php echo e(Auth::user()->username); ?>

                                </a>
                            <?php else: ?>
                                <script>
                                    window.location.href = "<?php echo e(route('dang-nhap')); ?>";
                                </script>
                            <?php endif; ?>
                        </div>

                        <div class="admin-manage">
                            <?php $role = Auth::user()->role ?? null; ?>

                            
                            <?php if($role === 0): ?>
                                <li class="<?php echo e(request()->routeIs('quan-ly-nguoi-dung') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-nguoi-dung')); ?>">Quản lý người dùng</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('quan-ly-loai-san*') || 
                                    request()->routeIs('them-loai-san*') || 
                                    request()->routeIs('luu-loai-san*') || 
                                    request()->routeIs('cap-nhat-loai-san*') || 
                                    request()->routeIs('update.type*') || 
                                    request()->routeIs('xoa-loai-san*')
                                ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-loai-san')); ?>">Quản lý loại sân</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('quan-ly-san*') || 
                                    request()->routeIs('cap-nhat-san*') || 
                                    request()->routeIs('them-san*') || 
                                    request()->routeIs('luu-san*') || 
                                    request()->routeIs('quan-ly-thoi-gian-san*') || 
                                    request()->routeIs('them-thoi-gian-san*') || 
                                    request()->routeIs('luu-thoi-gian-san*') || 
                                    request()->routeIs('cap-nhat-thoi-gian-san*') || 
                                    request()->routeIs('quan-ly-hinh-anh-san*') || 
                                    request()->routeIs('them-hinh-anh-san*') || 
                                    request()->routeIs('luu-hinh-anh-san*') || 
                                    request()->routeIs('cap-nhat-hinh-anh-san*') || 
                                    request()->routeIs('thong-tin-don-vi-thau*') || 
                                    request()->routeIs('cap-nhat-thong-tin-don-vi-thau*')
                                ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-san')); ?>">Quản lý sân</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs([
                                        'quan-ly-don-dat-san-co-dinh',
                                        'cap-nhat-don-dat-san-co-dinh',
                                        'xoa-don-dat-san-co-dinh',
                                        'cap-nhat-chi-tiet-don-dat-san-co-dinh',
                                        'update.fixedorder_detail'
                                    ]) ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-don-dat-san-co-dinh')); ?>">Đơn cố định</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs([
                                        'quan-ly-don-dat-san',
                                        'cap-nhat-trang-thai-don-dat-san',
                                        'cap-nhat-don-dat-san',
                                        'xoa-don-dat-san',

                                        'cap-nhat-chi-tiet-don',
                                        'yards.by.type',
                                        'times.by.yard',
                                        'update.order_detail',
                                        'xoa-chi-tiet-don',
                                    ]) ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-don-dat-san')); ?>">Đơn đặt sân</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('quan-ly-tin-tuc*') || 
                                    request()->routeIs('cap-nhat-trang-thai-tin-tuc*') || 
                                    request()->routeIs('them-tin-tuc*') || 
                                    request()->routeIs('luu-tin-tuc*') || 
                                    request()->routeIs('cap-nhat-tin-tuc*') || 
                                    request()->routeIs('update.news*') || 
                                    request()->routeIs('xoa-tin-tuc*') || 
                                    request()->routeIs('xoa-noi-dung*') || 
                                    request()->routeIs('delete.news.image*') ||
                                    request()->routeIs('quan-ly-loai-tin-tuc*') || 
                                    request()->routeIs('them-loai-tin-tuc*') || 
                                    request()->routeIs('luu-loai-tin-tuc*') || 
                                    request()->routeIs('cap-nhat-loai-tin-tuc*') || 
                                    request()->routeIs('update.news_type*') || 
                                    request()->routeIs('xoa-loai-tin-tuc*')
                                ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-tin-tuc')); ?>">Tin tức</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('thong-tin-thanh-toan') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('thong-tin-thanh-toan')); ?>">Thanh toán</a>
                                </li>
                            <?php endif; ?>

                            
                            <?php if($role === 2): ?>
                                <li class="<?php echo e(request()->routeIs('quan-ly-nguoi-dung') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-nguoi-dung')); ?>">Quản lý người dùng</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('quan-ly-san') || 
                                    request()->routeIs('cap-nhat-san') || 
                                    request()->routeIs('them-san') || 
                                    request()->routeIs('luu-san') || 
                                    request()->routeIs('quan-ly-thoi-gian-san') || 
                                    request()->routeIs('them-thoi-gian-san') || 
                                    request()->routeIs('luu-thoi-gian-san') || 
                                    request()->routeIs('cap-nhat-thoi-gian-san') || 
                                    request()->routeIs('quan-ly-hinh-anh-san') || 
                                    request()->routeIs('them-hinh-anh-san') || 
                                    request()->routeIs('luu-hinh-anh-san') || 
                                    request()->routeIs('cap-nhat-hinh-anh-san') || 
                                    request()->routeIs('thong-tin-don-vi-thau') || 
                                    request()->routeIs('cap-nhat-thong-tin-don-vi-thau')
                                ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-san')); ?>">Quản lý sân</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs([
                                        'quan-ly-don-dat-san-co-dinh',
                                        'cap-nhat-don-dat-san-co-dinh',
                                        'xoa-don-dat-san-co-dinh',
                                        'cap-nhat-chi-tiet-don-dat-san-co-dinh',
                                        'update.fixedorder_detail'
                                    ]) ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-don-dat-san-co-dinh')); ?>">Đơn cố định</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs([
                                        'quan-ly-don-dat-san',
                                        'cap-nhat-trang-thai-don-dat-san',
                                        'cap-nhat-don-dat-san',
                                        'xoa-don-dat-san',

                                        'cap-nhat-chi-tiet-don',
                                        'yards.by.type',
                                        'times.by.yard',
                                        'update.order_detail',
                                        'xoa-chi-tiet-don',
                                    ]) ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-don-dat-san')); ?>">Đơn đặt sân</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('quan-ly-cua-hang*') || 
                                    request()->routeIs('cap-nhat-trang-thai-cua-hang*') || 
                                    request()->routeIs('them-cua-hang*') || 
                                    request()->routeIs('luu-cua-hang*') || 
                                    request()->routeIs('cap-nhat-thong-tin-cua-hang*') || 
                                    request()->routeIs('update.stores*') || 
                                    request()->routeIs('xoa-cua-hang*') || 

                                    request()->routeIs('quan-ly-loai-san-pham*') || 
                                    request()->routeIs('them-loai-san-pham*') || 
                                    request()->routeIs('luu-loai-san-pham*') || 
                                    request()->routeIs('cap-nhat-loai-san-pham*') || 
                                    request()->routeIs('update.loai-san-pham*') || 
                                    request()->routeIs('xoa-loai-san-pham*') || 

                                    request()->routeIs('quan-ly-san-pham*') || 
                                    request()->routeIs('cap-nhat-trang-thai-san-pham*') || 
                                    request()->routeIs('them-san-pham*') || 
                                    request()->routeIs('luu-san-pham*') || 
                                    request()->routeIs('cap-nhat-san-pham*') || 
                                    request()->routeIs('update.san-pham*') || 
                                    request()->routeIs('xoa-san-pham*') 
                                ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-cua-hang')); ?>">Quản lý cửa hàng</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('quan-ly-don-mua-hang*') || 
                                        request()->routeIs('cap-nhat-trang-thai-don-mua-hang*') || 
                                        request()->routeIs('cap-nhat-don-mua-hang*') || 
                                        request()->routeIs('xoa-don-mua-hang*') || 
                                        request()->routeIs('cap-nhat-chi-tiet-don-mua-hang*') || 
                                        request()->routeIs('update-chi-tiet-don-mua-hang*') || 
                                        request()->routeIs('xoa-chi-tiet-don-mua-hang*')
                                    ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-don-mua-hang')); ?>">Đơn mua hàng</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('quan-ly-tin-tuc*') || 
                                    request()->routeIs('cap-nhat-trang-thai-tin-tuc*') || 
                                    request()->routeIs('them-tin-tuc*') || 
                                    request()->routeIs('luu-tin-tuc*') || 
                                    request()->routeIs('cap-nhat-tin-tuc*') || 
                                    request()->routeIs('update.news*') || 
                                    request()->routeIs('xoa-tin-tuc*') || 
                                    request()->routeIs('xoa-noi-dung*') || 
                                    request()->routeIs('delete.news.image*') ||
                                    request()->routeIs('quan-ly-loai-tin-tuc*') || 
                                    request()->routeIs('them-loai-tin-tuc*') || 
                                    request()->routeIs('luu-loai-tin-tuc*') || 
                                    request()->routeIs('cap-nhat-loai-tin-tuc*') || 
                                    request()->routeIs('update.news_type*') || 
                                    request()->routeIs('xoa-loai-tin-tuc*')
                                ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-tin-tuc')); ?>">Tin tức</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('thong-tin-thanh-toan') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('thong-tin-thanh-toan')); ?>">Thanh toán</a>
                                </li>
                            <?php endif; ?>

                            
                            <?php if($role === 3): ?>
                                <li class="<?php echo e(request()->routeIs('quan-ly-san') || 
                                    request()->routeIs('cap-nhat-san') || 
                                    request()->routeIs('them-san') || 
                                    request()->routeIs('luu-san') || 
                                    request()->routeIs('quan-ly-thoi-gian-san*') || 
                                    request()->routeIs('them-thoi-gian-san*') || 
                                    request()->routeIs('luu-thoi-gian-san*') || 
                                    request()->routeIs('cap-nhat-thoi-gian-san*') || 
                                    request()->routeIs('quan-ly-hinh-anh-san*') || 
                                    request()->routeIs('them-hinh-anh-san*') || 
                                    request()->routeIs('luu-hinh-anh-san*') || 
                                    request()->routeIs('cap-nhat-hinh-anh-san*') || 
                                    request()->routeIs('thong-tin-don-vi-thau*') || 
                                    request()->routeIs('cap-nhat-thong-tin-don-vi-thau*')
                                ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-san')); ?>">Quản lý sân</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs([
                                        'quan-ly-don-dat-san-co-dinh',
                                        'cap-nhat-don-dat-san-co-dinh',
                                        'xoa-don-dat-san-co-dinh',
                                        'cap-nhat-chi-tiet-don-dat-san-co-dinh',
                                        'update.fixedorder_detail'
                                    ]) ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-don-dat-san-co-dinh')); ?>">Đơn cố định</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs([
                                        'quan-ly-don-dat-san',
                                        'cap-nhat-trang-thai-don-dat-san',
                                        'cap-nhat-don-dat-san',
                                        'xoa-don-dat-san',

                                        'cap-nhat-chi-tiet-don',
                                        'yards.by.type',
                                        'times.by.yard',
                                        'update.order_detail',
                                        'xoa-chi-tiet-don',
                                    ]) ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-don-dat-san')); ?>">Đơn đặt sân</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('quan-ly-cua-hang*') || 
                                    request()->routeIs('cap-nhat-trang-thai-cua-hang*') || 
                                    request()->routeIs('them-cua-hang*') || 
                                    request()->routeIs('luu-cua-hang*') || 
                                    request()->routeIs('cap-nhat-thong-tin-cua-hang*') || 
                                    request()->routeIs('update.stores*') || 
                                    request()->routeIs('xoa-cua-hang*') || 

                                    request()->routeIs('quan-ly-loai-san-pham*') || 
                                    request()->routeIs('them-loai-san-pham*') || 
                                    request()->routeIs('luu-loai-san-pham*') || 
                                    request()->routeIs('cap-nhat-loai-san-pham*') || 
                                    request()->routeIs('update.loai-san-pham*') || 
                                    request()->routeIs('xoa-loai-san-pham*') || 

                                    request()->routeIs('quan-ly-san-pham*') || 
                                    request()->routeIs('cap-nhat-trang-thai-san-pham*') || 
                                    request()->routeIs('them-san-pham*') || 
                                    request()->routeIs('luu-san-pham*') || 
                                    request()->routeIs('cap-nhat-san-pham*') || 
                                    request()->routeIs('update.san-pham*') || 
                                    request()->routeIs('xoa-san-pham*') 
                                ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-cua-hang')); ?>">Quản lý cửa hàng</a>
                                </li>
                                <li class="<?php echo e(request()->routeIs('quan-ly-don-mua-hang*') || 
                                        request()->routeIs('cap-nhat-trang-thai-don-mua-hang*') || 
                                        request()->routeIs('cap-nhat-don-mua-hang*') || 
                                        request()->routeIs('xoa-don-mua-hang*') || 
                                        request()->routeIs('cap-nhat-chi-tiet-don-mua-hang*') || 
                                        request()->routeIs('update-chi-tiet-don-mua-hang*') || 
                                        request()->routeIs('xoa-chi-tiet-don-mua-hang*')
                                    ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-don-mua-hang')); ?>">Đơn mua hàng</a>
                                </li>

                                <li class="<?php echo e(request()->routeIs('quan-ly-tin-tuc*') || 
                                    request()->routeIs('cap-nhat-trang-thai-tin-tuc*') || 
                                    request()->routeIs('them-tin-tuc*') || 
                                    request()->routeIs('luu-tin-tuc*') || 
                                    request()->routeIs('cap-nhat-tin-tuc*') || 
                                    request()->routeIs('update.news*') || 
                                    request()->routeIs('xoa-tin-tuc*') || 
                                    request()->routeIs('xoa-noi-dung*') || 
                                    request()->routeIs('delete.news.image*') ||
                                    request()->routeIs('quan-ly-loai-tin-tuc*') || 
                                    request()->routeIs('them-loai-tin-tuc*') || 
                                    request()->routeIs('luu-loai-tin-tuc*') || 
                                    request()->routeIs('cap-nhat-loai-tin-tuc*') || 
                                    request()->routeIs('update.news_type*') || 
                                    request()->routeIs('xoa-loai-tin-tuc*')
                                ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('quan-ly-tin-tuc')); ?>">Tin tức</a>
                                </li>
                            <?php endif; ?>

                            
                            <li class="<?php echo e(request()->routeIs('thong-ke-bao-cao') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('thong-ke-bao-cao')); ?>">Thống kê, báo cáo</a>
                            </li>
                            <li class="<?php echo e(request()->routeIs('thong-tin-tai-khoan') ? 'active' : ''); ?>">
                                <a href="<?php echo e(route('thong-tin-tai-khoan')); ?>">Quản lý tài khoản</a>
                            </li>
                        </div>
                    </div>
                </div>

                <div class="admin">
                    <div class="admin-section-right">
                        <!-- Nội dung chính sẽ được chèn ở đây -->
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!-- End: Content -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Designed by M</p>
        </div>
        <!-- End: Footer -->

    </div>
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <!-- Xem ảnh trong admin -->
    <div id="image-popup" onclick="hideImage()" style="
        display: none;
        position: fixed;
        z-index: 9999;
        top: 0; left: 0; width: 100%; height: 100%;
        background-color: rgba(0,0,0,0.8);
        justify-content: center;
        align-items: center;
    ">
        <img id="popup-img" src="" style="width: auto; height: 90%;" onclick="event.stopPropagation()">
    </div>

    <script>
        function showImage(src) {
            const popup = document.getElementById('image-popup');
            const popupImg = document.getElementById('popup-img');
            popupImg.src = src;
            popup.style.display = 'flex';
        }

        function hideImage() {
            document.getElementById('image-popup').style.display = 'none';
        }
    </script>
</body>
</html>
<?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/layouts/admin.blade.php ENDPATH**/ ?>