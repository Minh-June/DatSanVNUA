

<?php $__env->startSection('title', 'Sửa thông tin sân'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-section">
        <h3>Sửa thông tin sân</h3>

        <!-- Form chỉnh sửa thông tin sân -->
        <div class="adminedit">
            <form method="POST" action="<?php echo e(route('yards.update', $yard->san_id)); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="_method" value="POST">
                <label for="tensan">Tên sân:</label>
                <input type="text" id="tensan" name="tensan" value="<?php echo e(old('tensan', $yard->tensan)); ?>" required>
                <br>
                <label for="sosan">Số sân:</label>
                <input type="text" id="sosan" name="sosan" value="<?php echo e(old('sosan', $yard->sosan)); ?>" required>
                <br>
                <button class="update-btn" type="submit">Cập nhật thông tin sân</button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/yards/update.blade.php ENDPATH**/ ?>