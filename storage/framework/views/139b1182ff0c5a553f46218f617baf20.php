<?php $__env->startSection('title', 'Thay đổi mật khẩu'); ?>

<?php $__env->startSection('content'); ?>  
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <script>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                alert("<?php echo e($error); ?>");
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </script>
    <?php endif; ?>

    <h2>Thay đổi mật khẩu</h2> 

    <div class="adminedit">
        <form method="POST" action="<?php echo e(route('cap-nhat-mat-khau')); ?>">
            <?php echo csrf_field(); ?>
            <div class="adminedit-form-group">
                <label>Mật khẩu hiện tại:</label>
                <input type="password" name="matkhau_hientai" required>
            </div>

            <div class="adminedit-form-group">
                <label>Nhập mật khẩu mới:</label>
                <input type="password" name="matkhau_moi" required>
            </div>
            
            <div class="adminedit-form-group">
                <label>Xác nhận mật khẩu mới:</label>
                <input type="password" name="xacnhan_matkhau" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Cập nhật mật khẩu</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/account/password.blade.php ENDPATH**/ ?>