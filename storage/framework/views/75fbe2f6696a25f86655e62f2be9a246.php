

<?php $__env->startSection('title', 'Cập nhật thông tin khách đặt sân thể thao'); ?>

<?php $__env->startSection('content'); ?>
    <h3>Cập nhật thông tin khách hàng</h3>

    <div class="adminedit">
        <form method="POST" action="<?php echo e(route('orders.update', $order->order_id)); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="order_id" value="<?php echo e($order->order_id); ?>">

            <div class="admin-time">
                <label for='san_id'>Chọn sân:</label>
                <select class="admin-time-select" name='san_id' required>
                    <?php $__currentLoopData = $sans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $san): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value='<?php echo e($san->san_id); ?>' <?php echo e($san->san_id == $order->san_id ? 'selected' : ''); ?>>
                            <?php echo e($san->tensan . " - " . $san->sosan); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select><br>
            </div>                        

            <label for='name'>Họ và tên:</label>
            <input type='text' name='name' value='<?php echo e($order->name); ?>' required><br>
            <label for='phone'>Số điện thoại:</label>
            <input type='text' name='phone' value='<?php echo e($order->phone); ?>' required><br>
            <label for='date'>Ngày:</label>
            <input type='date' name='date' value='<?php echo e($order->date); ?>' required><br>
            <label for='time'>Thời gian:</label>
            <input type='text' name='time' value='<?php echo e($order->time); ?>' required><br>
            <label for='price'>Thành tiền:</label>
            <input type='text' name='price' value='<?php echo e($order->price); ?>' required><br>
            <label for='notes'>Ghi chú:</label><br><br>
            <textarea name='notes' rows='4' cols='50'><?php echo e($order->notes); ?></textarea><br>
            <input type='submit' class="update-btn" value='Cập nhật thông tin khách hàng'>
        </form>                          
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/orders/update.blade.php ENDPATH**/ ?>