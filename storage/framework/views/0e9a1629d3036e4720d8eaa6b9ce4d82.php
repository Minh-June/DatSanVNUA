<?php $__env->startSection('title', 'Sửa thông tin sân'); ?>

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

    <h2>Sửa thông tin sân</h2>

    <!-- Form chỉnh sửa thông tin sân -->
    <div class="adminedit">
        <form method="POST" action="<?php echo e(route('update.yard', $yard->yard_id)); ?>">
            <?php echo csrf_field(); ?>

            <div class="adminedit-form-group">
                <label for="type_id">Thể loại sân:</label>
                <select id="type_id" name="type_id" required>
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->type_id); ?>" <?php echo e($yard->type_id == $type->type_id ? 'selected' : ''); ?>>
                            <?php echo e($type->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="adminedit-form-group">
                <label for="name">Tên sân:</label>
                <input type="text" id="name" name="name" value="<?php echo e(old('name', $yard->name)); ?>" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Cập nhật thông tin</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/yards/update.blade.php ENDPATH**/ ?>