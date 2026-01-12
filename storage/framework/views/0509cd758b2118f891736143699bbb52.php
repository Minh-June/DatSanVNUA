

<?php $__env->startSection('title', 'Cập nhật thông tin cửa hàng'); ?>

<?php $__env->startSection('content'); ?>
    <h2>Cập nhật thông tin cửa hàng</h2>

    <div class="adminedit">
        <form method="POST" action="<?php echo e(route('update.stores', $store->store_id)); ?>">
            <?php echo csrf_field(); ?>
            <div class="adminedit-form-group">
                <label>Tên cửa hàng:</label>
                <input type="text" name="name" value="<?php echo e($store->name); ?>" required>
            </div>

            <div class="adminedit-button">
                <button type="submit" class="update-btn">Lưu thông tin</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/stores/update.blade.php ENDPATH**/ ?>