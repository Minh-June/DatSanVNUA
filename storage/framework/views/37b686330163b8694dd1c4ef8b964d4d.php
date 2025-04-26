

<?php $__env->startSection('title', 'Thêm hình ảnh sân'); ?>

<?php $__env->startSection('content'); ?>
    <h3>Thêm hình ảnh sân thể thao</h3>

    <div class="adminedit">
        <form action="<?php echo e(route('store.hinh-anh-san')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div>
                <label for="san_id">Sân thể thao:</label>
                <select class="admin-time-select" name="san_id" required>
                    <option value="">Chọn sân</option>
                    <?php $__currentLoopData = $available_san_ids; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $san): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($san->san_id); ?>"><?php echo e($san->tensan); ?> - <?php echo e($san->sosan); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['san_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="error"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
            <br>
            <div>
                <label for="image">Chọn hình ảnh:</label>
                <input type="file" name="image" id="image" required>
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
            </div>
            <br>
            <div>
                <button type="submit" class="update-btn">Thêm ảnh</button>
            </div>
        </form>                                                      
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/imgyards/create.blade.php ENDPATH**/ ?>