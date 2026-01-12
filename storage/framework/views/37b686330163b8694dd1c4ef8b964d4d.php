

<?php $__env->startSection('title', 'Thêm hình ảnh sân thể thao'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <h2>Thêm hình ảnh sân thể thao</h2>

    <div class="adminedit">
        <form action="<?php echo e(route('luu-hinh-anh-san')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="adminedit-form-group">
                <label for="yard_id">Sân thể thao:</label>
                <input type="text" class="admin-time-select" value="<?php echo e($selectedYard->name); ?>" disabled>
                <input type="hidden" name="yard_id" value="<?php echo e($selectedYard->yard_id); ?>">
            </div>

            <div class="adminedit-form-group">
                <label for="image">Chọn hình ảnh:</label>
                <input type="file" name="image" id="image" required>
            </div>
            <div class="adminedit-button">
                <button type="submit" class="update-btn">Thêm hình ảnh</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/imgyards/create.blade.php ENDPATH**/ ?>