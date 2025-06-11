

<?php $__env->startSection('title', 'Đăng Ký'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo thành công -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <!-- Hiển thị thông báo lỗi -->
    <?php if(session('error')): ?>
        <script>
            alert("<?php echo e(session('error')); ?>");
        </script>
    <?php endif; ?>

    <div class="container-access" id="signIn">
        <h1 class="form-title">Đăng Ký</h1>

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
        <br>
        <form method="post" action="<?php echo e(route('dang-ky')); ?>">
            <?php echo csrf_field(); ?>            
            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" id="fullname" name="fullname" placeholder="Họ và tên" required>
                <label class="label-access" for="fullname"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-genderless input-group-icon"></i>
                <label class="input-group-select" for="gender">Giới tính:</label>
                <select class="login-time-select" id="gender" name="gender" required>
                    <option value="" disabled selected>Chọn</option>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                    <option value="Khác">Khác</option>
                </select>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-calendar input-group-icon"></i>
                <label class="input-group-select" for="birthdate">Ngày sinh:</label>
                <input class="login-time-select" type="date" id="birthdate" name="birthdate" required>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-phone"></i>
                <input type="text" id="phonenb" name="phonenb" placeholder="Số điện thoại" required>
                <label class="label-access" for="phonenb"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <label class="label-access" for="email"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-regular fa-user"></i>
                <input type="text" name="username" id="username" placeholder="Tên người dùng" required>
                <label class="label-access" for="username"></label>
            </div>
        
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
                <label class="label-access" for="password"></label>
            </div>
        
            <input type="submit" class="index-btn" value="Đăng Ký" name="btnDangky">
        </form>        

        <div class="links">
            <p>Bạn đã có tài khoản ?</p>
            <a href="<?php echo e(route('dang-nhap')); ?>"><button id="signUpButton">Đăng Nhập</button></a>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/auth/register.blade.php ENDPATH**/ ?>