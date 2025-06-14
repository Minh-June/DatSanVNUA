

<?php $__env->startSection('title', 'Thêm khung giờ sân'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo -->
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <?php if($errors->has('time')): ?>
        <script>alert("<?php echo e($errors->first('time')); ?>");</script>
    <?php endif; ?>

    <h2>Thêm khung giờ cho thuê</h2>

    <!-- Form thêm khung giờ -->
    <div class="adminedit">
        <form action="<?php echo e(route('luu-thoi-gian-san')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="yard_id" value="<?php echo e($yard_id); ?>">
            <div class="adminedit-form-group">
                <label>Sân:</label>
                <input type="text" value="<?php echo e($yards->firstWhere('yard_id', $yard_id)?->name); ?>" disabled>
            </div>

            <div class="adminedit-form-group">
                <label for="time">Khung giờ:</label>
                <input
                    type="text"
                    id="time"
                    name="time"
                    required
                    pattern="\d{2}:\d{2}\s*-\s*\d{2}:\d{2}"
                    title="Định dạng phải là HH:MM - HH:MM (VD: 06:00 - 07:30)"
                    placeholder="Ví dụ: 06:00 - 07:30"
                >
            </div>

            <div class="adminedit-form-group">
                <label for="price">Giá tiền (đ):</label>
                <input type="number" id="price" name="price" required step="1000">
            </div>

            <div class="adminedit-form-group">
                <label for="date">Ngày áp dụng:</label>
                <input type="date" id="date" name="date" required min="<?php echo e(date('Y-m-d')); ?>" value="<?php echo e(date('Y-m-d')); ?>">
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Lưu khung giờ</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/timeyards/create.blade.php ENDPATH**/ ?>