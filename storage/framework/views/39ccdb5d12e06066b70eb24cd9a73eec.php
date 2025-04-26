

<?php $__env->startSection('title', 'Cập nhật hình ảnh sân'); ?>

<?php $__env->startSection('content'); ?>
    <h3>Cập nhật hình ảnh sân</h3>

    <?php if(session('success')): ?>
        <script>
            alert('<?php echo e(session('success')); ?>');
        </script>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('sua-hinh-anh-san', ['image_id' => $image->image_id])); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?> <!-- Thêm CSRF token nếu cần -->
        <div class="pay-information">
            <div class="admin-img">
                <img src="<?php echo e(asset(Storage::url($image->image))); ?>" alt="Hình ảnh" class="admin-image">
            </div>
        </div>

        <div class="pay-information">
            <div class="admin-img">
                <h3><?php echo e($tensan . ' - ' . $sosan); ?></h3><br><br>
                <label for="image">Chọn hình ảnh mới:</label><br><br>
                <input class="admin-time-select" type="file" name="image" id="image"><br><br>
                <input type="submit" class="update-btn" value="Cập nhật hình ảnh sân">
            </div>
        </div>
    </form>                        
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/imgyards/update.blade.php ENDPATH**/ ?>