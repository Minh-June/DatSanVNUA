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
            <a class="home-heading" href="<?php echo e(route('trang-chu')); ?>" target="_top">Äáº·t sĂ¢n thá»ƒ thao</a>
            
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
                                    // Key nhĂ³m theo sĂ¢n vĂ  ngĂ y: yard_id + date
                                    $key = $order['yard_id'] . '_' . $order['date'];
                                    if (!isset($groupedOrders[$key])) {
                                        $groupedOrders[$key] = $order;
                                        // Máº£ng lÆ°u táº¥t cáº£ giá» Ä‘Ă£ chá»n cho nhĂ³m nĂ y
                                        $groupedOrders[$key]['times'] = $order['times'];
                                    } else {
                                        // Ná»‘i thĂªm cĂ¡c giá» má»›i (loáº¡i bá» trĂ¹ng)
                                        $groupedOrders[$key]['times'] = array_unique(array_merge($groupedOrders[$key]['times'], $order['times']));
                                        // Cá»™ng dá»“n giĂ¡ tiá»n
                                        $groupedOrders[$key]['price'] += $order['price'];
                                    }
                                }
                            }
                        ?>

                        <?php if(empty($groupedOrders)): ?>
                            <div class="header__cart-list header__cart-list--no-cart">
                                <div class="header__cart-list-no-cart-msg">ChÆ°a cĂ³ sĂ¢n vĂ  khung giá» Ä‘Æ°á»£c Ä‘áº·t</div>
                            </div>
                        <?php else: ?>
                            <div class="header__cart-list">
                                <div class="header__cart-heading">CĂ¡c sĂ¢n vĂ  khung giá» Ä‘Ă£ Ä‘áº·t</div>
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
                                                        <span class="header__cart-item-price"><?php echo e(number_format($order['price'], 0, ',', '.')); ?>Ä‘</span>
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

                                <a href="<?php echo e(route('xac-nhan-dat-san')); ?>" class="header__cart-view-cart">XĂ¡c nháº­n Ä‘áº·t sĂ¢n</a>
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
                    <a class="signup-btn" href="<?php echo e(route('dang-nhap')); ?>" target="_self">ÄÄƒng Nháº­p</a>
                <?php endif; ?>
            </div>
        </div>
        <!-- End: Header -->

        <?php echo $__env->yieldContent('content'); ?> <!-- NÆ¡i Ä‘á»ƒ ná»™i dung cá»§a cĂ¡c trang khĂ¡c Ä‘Æ°á»£c chĂ¨n vĂ o -->

        <!-- Begin: Footer -->
        <div id="footer">
            <p class="copyright">Powered by Group 48</p>
        </div>
        <!-- End: Footer -->

    </div>

</body>
</html>
<?php /**PATH D:\laragon\www\qldatsan\resources\views/layouts/client/client.blade.php ENDPATH**/ ?>
