

<?php $__env->startSection('title', 'Thông tin thanh toán'); ?>

<?php $__env->startSection('content'); ?>  
<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>

<?php if($errors->any()): ?>
    <script>alert("<?php echo e($errors->first()); ?>");</script>
<?php endif; ?>

<h2>Thông tin thanh toán</h2>

<div class="adminedit">
    <form method="POST" action="<?php echo e(route('cap-nhat-thong-tin-thanh-toan')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="adminedit-form-group">
            <label for="acc_name">Tên tài khoản:</label>
            <input type="text" name="acc_name" value="<?php echo e(old('acc_name', $user->acc_name ?? '')); ?>" required>
        </div>

        <div class="adminedit-form-group">
            <label for="acc_number">Số tài khoản:</label>
            <input type="text" name="acc_number" value="<?php echo e(old('acc_number', $user->acc_number ?? '')); ?>" required>
        </div>

        <div class="adminedit-form-group">
            <label for="acc_type">Ngân hàng:</label>
            <input type="text" name="acc_type" value="<?php echo e(old('acc_type', $user->acc_type ?? '')); ?>" placeholder="VD: Techcombank, Vietinbank..." required>
        </div>

        <div class="adminedit-form-group">
            <p>Mã QR hiện tại:</p>
            <?php if(!empty($user->qr_code)): ?>
                <img src="<?php echo e(asset('storage/' . $user->qr_code)); ?>" alt="QR code" style="max-width: 200px; border-radius: 8px;">
            <?php else: ?>
                <p style="margin:0 0 20px 90px;">Hiện chưa có</p>
            <?php endif; ?>
        </div>

        <div class="adminedit-form-group">
            <label for="qr_code">Mã QR:</label>
            <input type="file" name="qr_code" accept="image/*">
            <?php if(!empty($user->qr_code)): ?>
            <?php endif; ?>
        </div>

        <div class="adminedit-button">
            <button class="update-btn" type="submit">Cập nhật thông tin</button>
        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/payinfo/index.blade.php ENDPATH**/ ?>