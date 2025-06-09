

<?php $__env->startSection('title', 'Sửa thông tin sân'); ?>

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

    <h3>Sửa thông tin sân</h3>

    <!-- Form chỉnh sửa thông tin sân -->
    <div class="adminedit">
        <form method="POST" action="<?php echo e(route('update.yard', $yard->yard_id)); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="POST">

            <label for="type_id">Thể loại sân:</label>
            <select id="type_id" name="type_id" required>
                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->type_id); ?>" <?php echo e($yard->type_id == $type->type_id ? 'selected' : ''); ?>>
                        <?php echo e($type->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <br>

            <label for="name">Tên sân:</label>
            <input type="text" id="name" name="name" value="<?php echo e(old('name', $yard->name)); ?>" required>
            <br>

            <button class="update-btn" type="submit">Cập nhật thông tin sân</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/yards/update.blade.php ENDPATH**/ ?>