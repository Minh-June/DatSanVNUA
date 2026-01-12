

<?php $__env->startSection('title', 'Thêm loại sản phẩm'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>
<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<h2>Thêm loại sản phẩm</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="<?php echo e(route('quan-ly-loai-san-pham', $store->store_id)); ?>">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="admin-add-btn"></div>
</div>

<div class="adminedit">
    <form method="POST" action="<?php echo e(route('luu-loai-san-pham', $store->store_id)); ?>">
        <?php echo csrf_field(); ?>
        <div class="adminedit-form-group">
            <label>Loại sản phẩm:</label>
            <input type="text" name="name" placeholder="Nhập tên loại sản phẩm..." required>
        </div>

        <div class="adminedit-button">
            <button type="submit" class="update-btn">Lưu thông tin</button>
        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/producttypes/create.blade.php ENDPATH**/ ?>