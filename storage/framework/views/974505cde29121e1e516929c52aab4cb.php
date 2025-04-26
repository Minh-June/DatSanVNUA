

<?php $__env->startSection('title', 'Thêm sân'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-section">
        <h3>Thêm sân</h3>

        <!-- Form thêm sân mới -->
        <div class="adminedit">
            <form action="<?php echo e(route('them-san')); ?>" method="POST">
                <?php echo csrf_field(); ?> <!-- Thêm CSRF token -->
                <label for="tensan">Tên sân mới:</label>
                <input type="text" id="tensan" name="tensan" required>
                <br>
                <label for="sosan">Số sân:</label>
                <input type="text" id="sosan" name="sosan" required>
                <br>
                <button class="update-btn" type="submit">Lưu thông tin sân</button>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/yards/create.blade.php ENDPATH**/ ?>