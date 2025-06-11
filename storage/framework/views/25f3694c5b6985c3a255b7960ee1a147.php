

<?php $__env->startSection('title', 'ÄÄƒng Nháº­p'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-access" id="signIn">
        <h1 class="form-title">ÄÄƒng Nháº­p</h1>

        <form method="post" action="<?php echo e(route('dang-nhap')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('post'); ?>

            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="text" name="username" id="username" placeholder="TĂªn ngÆ°á»i dĂ¹ng" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Máº­t kháº©u" required>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="alert">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <input type="submit" class="index-btn" value="ÄÄƒng Nháº­p">
        </form>

        <div class="links">
            <p>Báº¡n chÆ°a cĂ³ tĂ i khoáº£n?</p>
            <a href="<?php echo e(route('dang-ky')); ?>"><button id="signUpButton">ÄÄƒng KĂ½</button></a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/auth/login.blade.php ENDPATH**/ ?>
