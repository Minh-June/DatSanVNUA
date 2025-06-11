

<?php $__env->startSection('title', 'Thêm sân'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <!-- Hiển thị thông báo lỗi -->
    <?php if($errors->any()): ?>
        <script>
            alert("<?php echo e($errors->first()); ?>");
        </script>
    <?php endif; ?>

    <h3>Thêm sân mới</h3>

    <!-- Form thêm sân mới -->
    <div class="adminedit">
        <form action="<?php echo e(route('luu-san')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <label for="type_id">Thể loại sân:</label>
            <select id="type_id" name="type_id" required>
                <option value="">Chọn loại sân</option>
                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->type_id); ?>"><?php echo e($type->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <br>
            <label for="name">Tên sân:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <button class="update-btn" type="submit">Lưu thông tin sân</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/yards/create.blade.php ENDPATH**/ ?>