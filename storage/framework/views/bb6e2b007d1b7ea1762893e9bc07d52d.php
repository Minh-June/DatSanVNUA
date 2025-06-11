

<?php $__env->startSection('title', 'Cáº­p nháº­t hĂ¬nh áº£nh sĂ¢n'); ?>

<?php $__env->startSection('content'); ?>
    <h3>Cáº­p nháº­t hĂ¬nh áº£nh</h3>

    <?php if(session('success')): ?>
        <script>
            alert('<?php echo e(session('success')); ?>');
        </script>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('cap-nhat-hinh-anh-san', ['image_id' => $image->image_id])); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="pay-information">
            <div class="admin-img">
                <!-- Hiá»ƒn thá»‹ áº£nh hiá»‡n táº¡i -->
                <img src="<?php echo e(asset('storage/' . $image->image)); ?>" alt="HĂ¬nh áº£nh" class="admin-image">
            </div>
        </div>

        <div class="pay-information">
            <div class="admin-img">
                <h3><?php echo e($image->yard->name); ?></h3>

                <label for="image">Chá»n hĂ¬nh áº£nh má»›i:</label><br><br>
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

                <button type="submit" class="update-btn">Cáº­p nháº­t</button>
            </div>
        </div>
    </form>                        
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/imgyards/update.blade.php ENDPATH**/ ?>
