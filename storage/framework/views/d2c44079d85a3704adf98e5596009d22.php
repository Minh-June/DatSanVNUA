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
            <a class="home-heading" href="<?php echo e(route('trang-chu')); ?>" target="_top">Đặt sân thể thao</a>
            
            <div class="header-login">
                <!-- Carlender layout -->
                <div class="header__carlender">
                    <div class="header__cart-wrap">
                        <i class="header__cart-icon fa-solid fa-calendar"></i>
                        <span class="header__cart-notice">
                            <?php echo e(session('orders') ? count(session('orders')) : 0); ?>

                        </span>

                        <?php
                            $groupedOrders = [];
                            if (session('orders')) {
                                foreach (session('orders') as $order) {
                                    // Key nhóm theo sân và ngày: yard_id + date
                                    $key = $order['yard_id'] . '_' . $order['date'];
                                    if (!isset($groupedOrders[$key])) {
                                        $groupedOrders[$key] = $order;
                                        // Mảng lưu tất cả giờ đã chọn cho nhóm này
                                        $groupedOrders[$key]['times'] = $order['times'];
                                    } else {
                                        // Nối thêm các giờ mới (loại bỏ trùng)
                                        $groupedOrders[$key]['times'] = array_unique(array_merge($groupedOrders[$key]['times'], $order['times']));
                                        // Cộng dồn giá tiền
                                        $groupedOrders[$key]['price'] += $order['price'];
                                    }
                                }
                            }
                        ?>

                        <?php if(empty($groupedOrders)): ?>
                            <div class="header__cart-list header__cart-list--no-cart">
                                <div class="header__cart-list-no-cart-msg">Chưa có sân và khung giờ được đặt</div>
                            </div>
                        <?php else: ?>
                            <div class="header__cart-list">
                                <div class="header__cart-heading">Các sân và khung giờ đã đặt</div>
                                <ul class="header__cart-list-item">
                                    <?php $__currentLoopData = $groupedOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="header__cart-item">
                                            <img 
                                                src="<?php echo e($yardFirstImages[$order['yard_id']] ?? asset('image/football.jpg')); ?>" 
                                                alt="<?php echo e($order['yard_name']); ?>" 
                                                class="header__cart-img"
                                            />

                                            <div class="header__cart-item-info">
                                                <div class="header__cart-item-head">
                                                    <div class="header__cart-item-name"><?php echo e($order['yard_name']); ?></div>
                                                    <div class="header__cart-item-price-wrap">
                                                        <span class="header__cart-item-price"><?php echo e(number_format($order['price'], 0, ',', '.')); ?>đ</span>
                                                        <span class="header__cart-item-multiply">x</span>
                                                        <span class="header__cart-item-qnt"><?php echo e(count($order['times'])); ?></span>
                                                    </div>
                                                </div>
                                                <div class="header__cart-item-body">
                                                    <span class="header__cart-item-remove">
                                                        <?php echo e(\Carbon\Carbon::parse($order['date'])->format('d/m/Y')); ?>

                                                    </span>
                                                    <span class="header__cart-item-description">
                                                        <?php echo implode('<br>', $order['times']); ?>

                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>

                                <a href="<?php echo e(route('xac-nhan-dat-san')); ?>" class="header__cart-view-cart">Xác nhận đặt sân</a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                
                <i class="login-btn dash"></i>
                <i class="avatar fa-solid fa-user-tie"></i>
                <?php if(Auth::check()): ?>
                    <?php
                        $user = Auth::user();
                    ?>
                    <a class="signup-btn" href="<?php echo e(route('thong-tin-tai-khoan')); ?>" target="_self">
                        <?php echo e($user->username); ?>

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
            <p class="copyright">Powered by Group 48</p>
        </div>
        <!-- End: Footer -->

    </div>

</body>
</html>
<?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/layouts/client/client.blade.php ENDPATH**/ ?>