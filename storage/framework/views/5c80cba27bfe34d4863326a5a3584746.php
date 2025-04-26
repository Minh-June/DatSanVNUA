<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đặt lịch sân thể thao</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('fonts/fontawesome-free-6.5.2/css/all.min.css')); ?>">
</head>
<body>
    <div id="main">
        
        <!-- Begin: Header -->
        <div id="header">
            <a class="home-heading" href="<?php echo e(route('admin')); ?>" target="_self">Đặt lịch sân thể thao</a>
            
            <div class="header-login">
                <i class="avatar fa-solid fa-user-tie"></i>
                <a class="signup-btn" href="<?php echo e(route('dang-nhap')); ?>" target="_self">Đăng xuất</a>
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
                            <a class="avatar-name" href="" target="_self" style="color: black;">Admin</a>
                        </div>
                        
                        <div class="admin-manage">
                            <li>
                                <a href="<?php echo e(route('quan-ly-khach-hang')); ?>" target="">Quản lý đơn đặt</a>
                                <ul class="section-left">
                                    <li><a href="<?php echo e(route('them-khach-hang')); ?>" target="">Thêm đơn đặt</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="<?php echo e(route('quan-ly-san')); ?>" target="">Quản lý sân</a>
                                <ul class="section-left">
                                    <li><a href="<?php echo e(route('them-san')); ?>" target="">Thêm sân</a></li>
                                </ul>
                            </li>
                            
                            <li>
                                <a href="<?php echo e(route('quan-ly-thoi-gian-san')); ?>" target="">Quản lý thời gian sân</a>
                                <ul class="section-left">
                                    <li><a href="<?php echo e(route('them-thoi-gian-san')); ?>" target="">Thêm thời gian sân</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="<?php echo e(route('quan-ly-hinh-anh-san')); ?>" target="">Quản lý hình ảnh sân</a>
                                <ul class="section-left">
                                    <li><a href="<?php echo e(route('them-hinh-anh-san')); ?>" target="">Thêm hình ảnh sân</a></li>
                                </ul>
                            </li>
                        </div> 
                        
                    </div>
                </div>

                <div class="admin">
                    <div class="admin-section-right">
                        <h3>Tổng quan</h3>

                    </div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        <!-- End: Content -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Powered by Group 7</p>
        </div>
        <!-- End: Footer -->
    </div>

</body>
</html><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/admin.blade.php ENDPATH**/ ?>