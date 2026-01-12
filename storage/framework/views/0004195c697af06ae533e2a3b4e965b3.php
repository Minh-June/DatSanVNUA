

<?php $__env->startSection('title', 'Đăng Ký'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>

<?php
    use Carbon\Carbon;
    $maxDate = Carbon::now()->subYears(13)->format('Y-m-d');      // Phải đủ 13 tuổi
    $minDate = Carbon::now()->subYears(100)->format('Y-m-d');     // Không quá 100 tuổi
?>

<div class="container-access-register" id="signUp">
    <h2 class="form-title">Đăng Ký</h2>

    <form method="post" action="<?php echo e(route('dang-ky')); ?>">
        <?php echo csrf_field(); ?>            

        
        <div class="input-group">
            <i class="fa-regular fa-user"></i>
            <input type="text" id="fullname" name="fullname" placeholder="Họ và tên" 
                   value="<?php echo e(old('fullname')); ?>" required>
        </div>
        <?php $__errorArgs = ['fullname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        
        <div class="input-group">
            <i class="fa-solid fa-genderless"></i>
            <label class="input-group-select" for="gender">Giới tính:</label>
            <select class="login-time-select" id="gender" name="gender" required>
                <option value="" disabled <?php echo e(old('gender') ? '' : 'selected'); ?>>Chọn</option>
                <option value="Nam" <?php echo e(old('gender')=='Nam'?'selected':''); ?>>Nam</option>
                <option value="Nữ" <?php echo e(old('gender')=='Nữ'?'selected':''); ?>>Nữ</option>
                <option value="Khác" <?php echo e(old('gender')=='Khác'?'selected':''); ?>>Khác</option>
            </select>
        </div>
        <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        
        <div class="input-group">
            <i class="fa-solid fa-calendar"></i>
            <label class="input-group-select" for="birthdate">Ngày sinh:</label>
            <input class="login-time-select" type="date" id="birthdate" name="birthdate"
                   min="<?php echo e($minDate); ?>" max="<?php echo e($maxDate); ?>" 
                   value="<?php echo e(old('birthdate')); ?>" required>
        </div>
        <?php $__errorArgs = ['birthdate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        
        <div class="input-group">
            <i class="fa-solid fa-phone"></i>
            <input type="text" id="phonenb" name="phonenb" placeholder="Số điện thoại" 
                   value="<?php echo e(old('phonenb')); ?>" required>
        </div>
        <?php $__errorArgs = ['phonenb'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        
        <div class="input-group">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" id="email" name="email" placeholder="Email" 
                   value="<?php echo e(old('email')); ?>" required>
        </div>
        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        
        <div class="input-group">
            <i class="fa-solid fa-user"></i>
            <input type="text" name="username" id="username" placeholder="Tên người dùng" 
                   value="<?php echo e(old('username')); ?>" required>
        </div>
        <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        
        <div class="input-group">
            <i class="fa-solid fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Mật khẩu" required>
            <i class="fa-regular fa-eye toggle-password" style="cursor: pointer; margin-left: -30px;"></i>
        </div>
        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="error"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

        <input type="submit" class="index-btn" value="Đăng Ký" name="btnDangky">
    </form>        

    <div class="links">
        <p>Bạn đã có tài khoản ?</p>
        <a href="<?php echo e(route('dang-nhap')); ?>"><button id="signUpButton">Đăng Nhập</button></a>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.toggle-password');
    const password = document.getElementById('password');
    if(toggle){
        toggle.addEventListener('click', function(){
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            toggle.classList.toggle('fa-eye-slash');
        });
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/auth/register.blade.php ENDPATH**/ ?>