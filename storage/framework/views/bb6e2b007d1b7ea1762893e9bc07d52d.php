

<?php $__env->startSection('title', 'Cập nhật hình ảnh sân'); ?>

<?php $__env->startSection('content'); ?>
    <h3>Cập nhật hình ảnh</h3>

    <?php if(session('success')): ?>
        <script>
            alert('<?php echo e(session('success')); ?>');
        </script>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('cap-nhat-hinh-anh-san', ['image_id' => $image->image_id])); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="pay-information">
            <div class="admin-img">
                <!-- Hiển thị ảnh hiện tại -->
                <img src="<?php echo e(asset('storage/' . $image->image)); ?>" alt="Hình ảnh" class="admin-image">
            </div>
        </div>

        <div class="pay-information">
            <div class="admin-img">
                <h3><?php echo e($image->yard->name); ?></h3>

                <label for="image">Chọn hình ảnh mới:</label><br><br>
                <input type="file" name="image" id="image"><br>

                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                <button type="submit" class="update-btn">Cập nhật</button>
            </div>
        </div>
    </form>                        
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/imgyards/update.blade.php ENDPATH**/ ?>