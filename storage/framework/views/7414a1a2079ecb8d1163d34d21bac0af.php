

<?php $__env->startSection('title', 'Quáº£n lĂ½ thĂ´ng tin cĂ¡ nhĂ¢n'); ?>

<?php $__env->startSection('content'); ?>  
    <?php if($errors->any()): ?>
        <script>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                alert("<?php echo e($error); ?>");
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </script>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <h3>Quáº£n lĂ½ thĂ´ng tin cĂ¡ nhĂ¢n</h3>

    <div class="adminedit">
        <form method="post" action="<?php echo e(route('cap-nhat-thong-tin-ca-nhan')); ?>">
            <?php echo csrf_field(); ?> <!-- ThĂªm token CSRF -->
            
            <label for="fullname">Há» vĂ  tĂªn:</label>
            <input type="text" name="fullname" value="<?php echo e($user->fullname); ?>" required><br>
            
            <label for="gender">Giá»›i tĂ­nh:</label>
            <select class="admin-time-select" name="gender" required>
                <option value="Nam" <?php echo e($user->gender == 'Nam' ? 'selected' : ''); ?>>Nam</option>
                <option value="Ná»¯" <?php echo e($user->gender == 'Ná»¯' ? 'selected' : ''); ?>>Ná»¯</option>
                <option value="KhĂ¡c" <?php echo e($user->gender == 'KhĂ¡c' ? 'selected' : ''); ?>>KhĂ¡c</option>
            </select><br>
            
            <label for="birthdate">NgĂ y sinh:</label>
            <input type="date" name="birthdate" value="<?php echo e($user->birthdate); ?>" required><br>
            
            <label for="phonenb">Sá»‘ Ä‘iá»‡n thoáº¡i:</label>
            <input type="text" name="phonenb" value="<?php echo e($user->phonenb); ?>" required><br>
            
            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo e($user->email); ?>" required><br>
            
            <button class="update-btn" type="submit">Cáº­p nháº­t thĂ´ng tin cĂ¡ nhĂ¢n</button>
        </form>                          
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/account/infor.blade.php ENDPATH**/ ?>
