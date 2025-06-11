

<?php $__env->startSection('title', 'Cập nhật khung giờ sân'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            alert("<?php echo e(session('error')); ?>");
        </script>
    <?php endif; ?>

    <h3>Cập nhật khung giờ</h3>

    <!-- Form cập nhật khung giờ -->
    <div class="adminedit">
        <form action="<?php echo e(route('update.time', ['time_id' => $time->time_id])); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <label for="yard_id">Chọn sân:</label>
            <select id="yard_id" name="yard_id" required>
                <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($yard->yard_id); ?>" <?php echo e($yard->yard_id == $time->yard_id ? 'selected' : ''); ?>>
                        <?php echo e($yard->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <br>

            <label for="time">Khung giờ:</label>
            <input type="text" id="time" name="time" required pattern="\d{2}:\d{2}\s*-\s*\d{2}:\d{2}" title="Định dạng phải là HH:MM - HH:MM">            <br>

            <label for="price">Giá (VNĐ):</label>
            <input type="number" id="price" name="price" value="<?php echo e($time->price); ?>" required min="0">
            <br>

            <label for="date">Ngày áp dụng:</label>
            <input type="date" id="date" name="date" value="<?php echo e($time->date); ?>" required>
            <br>

            <button class="update-btn" type="submit">Cập nhật khung giờ</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/timeyards/update.blade.php ENDPATH**/ ?>