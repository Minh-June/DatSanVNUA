

<?php $__env->startSection('title', 'Cập nhật loại tin tức'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo thành công -->
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <!-- Hiển thị thông báo lỗi validation -->
    <?php if($errors->any()): ?>
        <script>alert("<?php echo e($errors->first()); ?>");</script>
    <?php endif; ?>

    <h2>Cập nhật loại tin tức</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <a class="update-btn" href="<?php echo e(route('quan-ly-loai-tin-tuc')); ?>">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="admin-add-btn"></div>
    </div>

    <!-- Form chỉnh sửa loại tin tức -->
    <div class="adminedit">
        <form method="POST" action="<?php echo e(route('update.news_type', $type->news_type_id)); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="POST">

            <div class="adminedit-form-group">
                <label for="name">Tên loại tin tức:</label>
                <input type="text" id="name" name="name" value="<?php echo e(old('name', $type->name)); ?>" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Cập nhật thông tin</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/newstype/update.blade.php ENDPATH**/ ?>