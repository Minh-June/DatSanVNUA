

<?php $__env->startSection('title', 'Thêm khách hàng mới'); ?>

<?php $__env->startSection('content'); ?>
    <h3>Thêm khách hàng mới</h3>

    <div class="adminedit">
        <form method="post" action="<?php echo e(route('store.order')); ?>">
            <?php echo csrf_field(); ?>
            <div class="admin-time">
                <label for='san_id'>Chọn sân:</label>
                <select class="admin-time-select" name='san_id' required>
                    <option value='' selected disabled>Chọn sân</option>
                    <?php $__currentLoopData = $sans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $san): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value='<?php echo e($san->san_id); ?>'>
                            <?php echo e($san->tensan); ?> - <?php echo e($san->sosan); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select><br>
            </div>

            <label for='name'>Họ và tên:</label>
            <input type='text' name='name' required><br>
            <label for='phone'>Số điện thoại:</label>
            <input type='text' name='phone' required><br>
            <label for='date'>Ngày:</label>
            <input type='date' name='date' required><br>
            <label for='time'>Thời gian:</label>
            <input type='text' name='time' required><br>
            <label for='price'>Thành tiền:</label>
            <input type='text' name='price' required><br>
            <label for='notes'>Ghi chú:</label><br><br>
            <textarea name='notes' rows='4' cols='50'></textarea><br>
            <input type='submit' class="update-btn" value='Thêm khách hàng mới'>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/orders/create.blade.php ENDPATH**/ ?>