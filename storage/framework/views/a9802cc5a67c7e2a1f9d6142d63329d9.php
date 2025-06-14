

<?php $__env->startSection('title', 'Đăng Nhập'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-access-login" id="signIn">
        <h2 class="form-title">Đăng Nhập</h2>

        <form method="post" action="<?php echo e(route('dang-nhap')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('post'); ?>

            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" id="username" placeholder="Tên người dùng" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="notice">
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <input type="submit" class="index-btn" value="Đăng Nhập">
        </form>

        <div class="links">
            <p>Bạn chưa có tài khoản?</p>
            <a href="<?php echo e(route('dang-ky')); ?>"><button id="signUpButton">Đăng Ký</button></a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/auth/login.blade.php ENDPATH**/ ?>