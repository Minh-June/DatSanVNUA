

<?php $__env->startSection('title', 'Thêm thời gian thuê sân'); ?>

<?php $__env->startSection('content'); ?>
    <div class="admin-section">
        <h3>Thêm thời gian thuê sân</h3>

        <!-- Begin: Form thêm khung giờ -->
        <div class="adminedit">
            <form method="POST" action="<?php echo e(route('store.time_slot')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="san_id">Chọn sân:</label>
                    <select class="admin-time-select" name="san_id" id="san_id">
                        <?php $__currentLoopData = $san_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $san): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($san->san_id); ?>"><?php echo e($san->tensan); ?> - <?php echo e($san->sosan); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div><br>
                
                <div class="form-group">
                    <label for="time_slot">Khung giờ:</label>
                    <input type="text" id="time_slot" name="time_slot" required><br>
                    <label for="price">Giá tiền:</label>
                    <input type="text" id="price" name="price" required>
                </div>
                <button class="admin-time-btn" type="submit" name="add_time_slot">Thêm khung giờ</button>
            </form>
        </div>
        <!-- End: Form thêm khung giờ -->
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/timeyards/create.blade.php ENDPATH**/ ?>