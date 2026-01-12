

<?php $__env->startSection('title', 'Thêm sân'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <script>alert("<?php echo e($errors->first()); ?>");</script>
    <?php endif; ?>

    <h2>Thêm sân mới</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <a class="update-btn" href="<?php echo e(route('quan-ly-san')); ?>">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="admin-add-btn"></div>
    </div>

    <!-- Form thêm sân mới -->
    <div class="adminedit">
        <form action="<?php echo e(route('luu-san')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="adminedit-form-group">
                <label for="type_id">Thể loại sân:</label>
                <select id="type_id" name="type_id" required>
                    <option value="">Chọn loại sân</option>
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->type_id); ?>" 
                            <?php echo e(old('type_id', $yard->type_id ?? '') == $type->type_id ? 'selected' : ''); ?>>
                            <?php echo e($type->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="adminedit-form-group">
                <label for="name">Tên sân:</label>
                <input type="text" id="name" name="name" 
                    value="<?php echo e(old('name', $yard->name ?? '')); ?>" required>
            </div>

            <div class="adminedit-button">
                <button class="update-btn" type="submit">Lưu thông tin</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/yards/create.blade.php ENDPATH**/ ?>