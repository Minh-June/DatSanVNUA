

<?php $__env->startSection('title', 'Thêm cửa hàng mới'); ?>

<?php $__env->startSection('content'); ?>
    <h2>Thêm cửa hàng mới</h2>

    <div class="adminedit">
        <form method="POST" action="<?php echo e(route('luu-cua-hang')); ?>">
            <?php echo csrf_field(); ?>
            <div class="adminedit-form-group">
                <label>Tên cửa hàng:</label>
                <input type="text" name="name" required>
            </div>
            <div class="adminedit-form-group">
                <label>Trạng thái:</label>
                <select name="status">
                    <option value="0">Hoạt động</option>
                    <option value="1">Ẩn</option>
                </select>
            </div>
            <div class="adminedit-button">
                <button type="submit" class="update-btn">Lưu thông tin</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/stores/create.blade.php ENDPATH**/ ?>