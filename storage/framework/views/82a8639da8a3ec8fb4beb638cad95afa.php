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
            <a class="home-heading" href="
                <?php if(Auth::check() && Auth::user()->role != 1): ?>
                    <?php echo e(route('admin')); ?>

                <?php else: ?>
                    <?php echo e(route('trang-chu')); ?>

                <?php endif; ?>
            " target="_top">Äáº·t sĂ¢n thá»ƒ thao</a>            
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
                                <?php
                                    $user = Auth::user();
                                ?>
                                <a class="avatar-name" href="<?php echo e(route('thong-tin-tai-khoan')); ?>" target="_self">
                                    <?php echo e($user->username); ?>

                                </a>
                            <?php else: ?>
                                <a class="avatar-name" href="<?php echo e(route('dang-nhap')); ?>" target="_self">
                                    ÄÄƒng Nháº­p
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="admin-manage">
                            <li>
                                <a href="<?php echo e(route('thong-tin-tai-khoan')); ?>" target="">Lá»‹ch sá»­ Ä‘áº·t sĂ¢n</a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('thong-tin-ca-nhan')); ?>" target="">ThĂ´ng tin cĂ¡ nhĂ¢n</a>
                            </li>
                            <li>
                                <a href="<?php echo e(route('thay-doi-mat-khau')); ?>" target="">Thay Ä‘á»•i máº­t kháº©u</a>
                            </li>
                            <li>
                                <a href="#" onclick="event.preventDefault(); handleAccountDelete();">XĂ³a tĂ i khoáº£n</a>
                            </li>

                            <!-- Form áº©n -->
                            <form id="delete-account-form" action="<?php echo e(route('xoa-tai-khoan')); ?>" method="POST" style="display: none;">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="password" id="delete-password">
                            </form>
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

    <!-- Lightbox hiá»ƒn thá»‹ áº£nh lá»›n -->
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
        // HĂ m hiá»ƒn thá»‹ áº£nh trong popup
        function showImage(src) {
            document.getElementById('popup-img').src = src;
            document.getElementById('image-popup').style.display = 'flex';
        }

        // HĂ m áº©n popup khi click vĂ o vĂ¹ng tá»‘i
        function hideImage() {
            document.getElementById('image-popup').style.display = 'none';
        }

        function handleAccountDelete() {
            if (confirm('Báº¡n cĂ³ cháº¯c cháº¯n muá»‘n xĂ³a tĂ i khoáº£n nĂ y khĂ´ng?')) {
                const password = prompt('Vui lĂ²ng nháº­p máº­t kháº©u Ä‘á»ƒ xĂ¡c nháº­n:');
                if (password) {
                    document.getElementById('delete-password').value = password;
                    document.getElementById('delete-account-form').submit();
                } else {
                    alert('Báº¡n chÆ°a nháº­p máº­t kháº©u. Há»§y thao tĂ¡c xĂ³a tĂ i khoáº£n.');
                }
            }
        }
    </script>
</body>
</html>
<?php /**PATH D:\laragon\www\qldatsan\resources\views/layouts/client/account.blade.php ENDPATH**/ ?>
