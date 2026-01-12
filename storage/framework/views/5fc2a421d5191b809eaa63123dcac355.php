

<?php $__env->startSection('title', 'Thêm size'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>

<?php if($errors->any()): ?>
    <script>alert("<?php echo e($errors->first()); ?>");</script>
<?php endif; ?>

<h2>Thêm size mới</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="<?php echo e(route('quan-ly-size', $product_id)); ?>">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="adminedit">
    <form action="<?php echo e(route('luu-size', $product_id)); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="adminedit-form-group">
            <label for="name">Tên size:</label>
            <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" required>
        </div>

        <div class="adminedit-form-group">
            <label for="price">Giá tiền (đ):</label>
            <input type="number" id="price" name="price" value="<?php echo e(old('price')); ?>" min="0" required>
        </div>

        <div class="adminedit-form-group">
            <label for="quantity">Số lượng:</label>
            <input type="number" id="quantity" name="quantity" value="<?php echo e(old('quantity')); ?>" min="0" required>
        </div>

        <div class="adminedit-button">
            <button class="update-btn" type="submit">Lưu thông tin</button>
        </div>
    </form>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/sizes/create.blade.php ENDPATH**/ ?>