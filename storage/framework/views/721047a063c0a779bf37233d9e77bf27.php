 

<?php $__env->startSection('title', 'Sửa thông tin loại sân'); ?>

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


    <h3>Sửa thông tin loại sân</h3>

    <!-- Form chỉnh sửa thông tin loại sân -->
    <div class="adminedit">
        <form method="POST" action="<?php echo e(route('update.type', $type->type_id)); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="POST">
            <label for="name">Tên loại sân:</label>
            <input type="text" id="name" name="name" value="<?php echo e(old('name', $type->name)); ?>" required>
            <br>
            <button class="update-btn" type="submit">Cập nhật thông tin loại sân</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/types/update.blade.php ENDPATH**/ ?>