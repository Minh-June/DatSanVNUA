

<?php $__env->startSection('title', 'Cập nhật hình ảnh sân'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error') || $errors->any()): ?>
        <script>
            alert(`<?php echo e(session('error') ? session('error') . '\n' : ''); ?><?php echo implode('\n', $errors->all()); ?>`);
        </script>
    <?php endif; ?>

    <h2>Cập nhật hình ảnh</h2>

    <form method="POST" action="<?php echo e(route('cap-nhat-hinh-anh-san', ['image_id' => $image->image_id])); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="pay-information">
            <div class="admin-img">
                <img src="<?php echo e(asset('storage/' . $image->image)); ?>" 
                    alt="Hình ảnh" 
                    class="football-img"
                    onclick="showImage(this.src)">
            </div>
        </div>

        <div class="pay-information">
            <div class="admin-img">
                <h2><?php echo e($image->yard->name); ?></h2>

                <h3 for="image">Chọn hình ảnh mới:</h3><br>
                <input type="file" name="image" id="image"><br>

                <button type="submit" class="update-btn">Cập nhật</button>
            </div>
        </div>
    </form>                        
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/imgyards/update.blade.php ENDPATH**/ ?>