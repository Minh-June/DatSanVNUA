

<?php $__env->startSection('title', 'Đăng Nhập'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-access" id="signIn">
        <h1 class="form-title">Đăng Nhập</h1>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger" role="alert">
                <strong>Có lỗi xảy ra !</strong>
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo e(route('dang-nhap')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('post'); ?>
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="username" name="username" id="username" placeholder="Tên người dùng" required>
            </div>
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
            </div>
            <input type="submit" class="index-btn" value="Đăng Nhập">
        </form>

        <div class="links">
            <p>Bạn chưa có tài khoản?</p>
            <a href="<?php echo e(route('dang-ky')); ?>"><button id="signUpButton">Đăng Ký</button></a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/auth/login.blade.php ENDPATH**/ ?>