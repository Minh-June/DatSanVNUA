

<?php $__env->startSection('title', 'Quản lý thông tin cá nhân'); ?>

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

    <h2>Quản lý thông tin cá nhân</h2>

    <div class="adminedit">
        <form method="post" action="<?php echo e(route('cap-nhat-thong-tin-ca-nhan')); ?>">
            <?php echo csrf_field(); ?>

            <div class="adminedit-form-group">
                <label for="fullname">Họ và tên:</label>
                <input type="text" name="fullname" value="<?php echo e($user->fullname); ?>" required>
            </div>

            <div class="adminedit-form-group">
                <label for="gender">Giới tính:</label>
                <select name="gender" required>
                    <option value="Nam" <?php echo e($user->gender == 'Nam' ? 'selected' : ''); ?>>Nam</option>
                    <option value="Nữ" <?php echo e($user->gender == 'Nữ' ? 'selected' : ''); ?>>Nữ</option>
                    <option value="Khác" <?php echo e($user->gender == 'Khác' ? 'selected' : ''); ?>>Khác</option>
                </select>
            </div>

            <div class="adminedit-form-group">
                <label for="birthdate">Ngày sinh:</label>
                <input type="date" name="birthdate" value="<?php echo e($user->birthdate); ?>" required>
            </div>

            <div class="adminedit-form-group">
                <label for="phonenb">Số điện thoại:</label>
                <input type="text" name="phonenb" value="<?php echo e($user->phonenb); ?>" required>
            </div>

            <div class="adminedit-form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo e($user->email); ?>" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Cập nhật thông tin</button>
            </div>
        </form>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/account/infor.blade.php ENDPATH**/ ?>