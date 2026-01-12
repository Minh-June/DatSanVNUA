

<?php $__env->startSection('title', 'Cập nhật khung giờ'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
<script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>

<?php if($errors->any()): ?>
<script>alert("<?php echo e($errors->first()); ?>");</script>
<?php endif; ?>

<h2>Cập nhật khung giờ cho thuê</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="<?php echo e($yard_id ? route('quan-ly-thoi-gian-san', ['yard_id' => $yard_id]) : route('quan-ly-san')); ?>">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="adminedit">
    <form action="<?php echo e(route('update.time', ['time_id' => $time->time_id])); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <input type="hidden" name="yard_id" value="<?php echo e($time->yard_id); ?>">

        
        <div class="adminedit-form-group">
            <label>Sân:</label>
            <input type="text" value="<?php echo e($yards->firstWhere('yard_id', $time->yard_id)?->name); ?>" disabled>
        </div>

        
        <div class="adminedit-form-group">
            <label for="start">Bắt đầu:</label>
            <input type="time"
                id="start"
                name="start"
                value="<?php echo e(old('start', \Carbon\Carbon::parse($time->start)->format('H:i'))); ?>"
                required step="60">
        </div>

        
        <div class="adminedit-form-group">
            <label for="end">Kết thúc:</label>
            <input type="time"
                id="end"
                name="end"
                value="<?php echo e(old('end', \Carbon\Carbon::parse($time->end)->format('H:i'))); ?>"
                required step="60">
        </div>

        
        <div class="adminedit-form-group">
            <label for="price_weekday">Giá T2-T6 (đ):</label>
            <input type="number"
                id="price_weekday"
                name="price_weekday"
                value="<?php echo e(old('price_weekday', $time->price_weekday)); ?>"
                step="1000" min="0">
        </div>

        
        <div class="adminedit-form-group">
            <label for="price_weekend">Giá T7-CN (đ):</label>
            <input type="number"
                id="price_weekend"
                name="price_weekend"
                value="<?php echo e(old('price_weekend', $time->price_weekend)); ?>"
                step="1000" min="0">
        </div>

        <div class="adminedit-button">
            <button class="update-btn" type="submit">Cập nhật thông tin</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/timeyards/update.blade.php ENDPATH**/ ?>