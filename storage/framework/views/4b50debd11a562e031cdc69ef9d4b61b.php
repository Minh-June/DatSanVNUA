<?php $__env->startSection('title', 'Thêm loại sân'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <!-- Hiển thị thông báo lỗi -->
    <?php if($errors->any()): ?>
        <script>
            alert("<?php echo e($errors->first()); ?>");
        </script>
    <?php endif; ?>

    <h2>Thêm loại sân</h2>

    <!-- Form thêm loại sân mới -->
    <div class="adminedit">
        <form action="<?php echo e(route('luu-loai-san')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="adminedit-form-group">
                <label for="name">Tên loại sân:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Lưu thông tin</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/types/create.blade.php ENDPATH**/ ?>