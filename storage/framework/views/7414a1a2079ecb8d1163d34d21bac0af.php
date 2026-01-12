

<?php $__env->startSection('title', 'Quản lý thông tin cá nhân'); ?>

<?php $__env->startSection('content'); ?>  
    <?php
        use Carbon\Carbon;
        $maxDate = Carbon::now()->subYears(13)->format('Y-m-d');
        $minDate = Carbon::now()->subYears(100)->format('Y-m-d');
    ?>

    <?php if($errors->any()): ?>
        <script>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                alert("<?php echo e($error); ?>");
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </script>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <h2>Quản lý thông tin cá nhân</h2>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            <?php if($user->image): ?>
                <form method="POST" action="<?php echo e(route('xoa-anh-dai-dien')); ?>" onsubmit="return confirm('Bạn có chắc muốn xóa ảnh đại diện không ?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="delete-btn">
                        <i class="fa-solid fa-xmark"></i>
                        Xóa ảnh đại diện
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="adminedit">
        <form method="post" action="<?php echo e(route('cap-nhat-thong-tin-ca-nhan')); ?>" enctype="multipart/form-data">
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
                <input type="date"
                       name="birthdate"
                       value="<?php echo e(old('birthdate', $user->birthdate)); ?>"
                       min="<?php echo e($minDate); ?>"
                       max="<?php echo e($maxDate); ?>"
                       required>
            </div>

            <div class="adminedit-form-group">
                <label for="phonenb">Số điện thoại:</label>
                <input type="text" name="phonenb" value="<?php echo e($user->phonenb); ?>" required>
            </div>

            <div class="adminedit-form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo e($user->email); ?>" required>
            </div>

            <?php if($user->role == 0): ?>
                <div class="adminedit-form-group">
                    <label for="www">Website:</label>
                    <input type="url" name="www" value="<?php echo e($user->www ?? ''); ?>" placeholder="https://example.com" required>
                </div>
            <?php endif; ?>

            <div class="adminedit-form-group"> 
                <p>Ảnh đại diện:</p> 
                <?php if($user->image): ?>
                    <img src="<?php echo e(asset('storage/' . $user->image)); ?>" alt="Avatar" width="120" style="border-radius: 8px; border: 1px solid #ccc;"> 
                <?php else: ?>
                    <p style="margin:0 0 20px 100px;">Hiện chưa có</p>
                <?php endif; ?>
            </div>

            <div class="adminedit-form-group">
                <label for="image">Cập nhật ảnh:</label>
                <input type="file" name="image" accept="image/*">
            </div>

            <div class="adminedit-button" style="margin-bottom:80px;">
                <button class="update-btn" type="submit">Cập nhật thông tin</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/account/infor.blade.php ENDPATH**/ ?>