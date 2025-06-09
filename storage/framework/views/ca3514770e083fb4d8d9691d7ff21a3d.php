

<?php $__env->startSection('title', 'Thêm khung giờ sân'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <!-- Hiển thị thông báo lỗi -->
    <?php if(session('error')): ?>
        <script>
            alert("<?php echo e(session('error')); ?>");
        </script>
    <?php endif; ?>

    <h3>Thêm khung giờ cho thuê</h3>

    <!-- Form thêm khung giờ -->
    <div class="adminedit">
        <form action="<?php echo e(route('luu-thoi-gian-san')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <label for="yard_id">Chọn sân:</label>
            <select id="yard_id" name="yard_id" required>
                <option value="">Chọn sân</option>
                <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($yard->yard_id); ?>"><?php echo e($yard->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <br>

            <label for="time">Khung giờ:</label>
            <input type="text" id="time" name="time" required>
            <br>

            <label for="price">Giá (VNĐ):</label>
            <input type="number" id="price" name="price" required min="0">
            <br>

            <label for="date">Ngày áp dụng:</label>
            <input type="date" id="date" name="date" required>
            <br>

            <button class="update-btn" type="submit">Lưu khung giờ</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/timeyards/create.blade.php ENDPATH**/ ?>