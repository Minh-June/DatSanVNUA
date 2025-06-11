

<?php $__env->startSection('title', 'ThĂªm sĂ¢n'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o lá»—i -->
    <?php if($errors->any()): ?>
        <script>
            alert("<?php echo e($errors->first()); ?>");
        </script>
    <?php endif; ?>

    <h3>ThĂªm sĂ¢n má»›i</h3>

    <!-- Form thĂªm sĂ¢n má»›i -->
    <div class="adminedit">
        <form action="<?php echo e(route('luu-san')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <label for="type_id">Thá»ƒ loáº¡i sĂ¢n:</label>
            <select id="type_id" name="type_id" required>
                <option value="">Chá»n loáº¡i sĂ¢n</option>
                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->type_id); ?>"><?php echo e($type->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <br>
            <label for="name">TĂªn sĂ¢n:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <button class="update-btn" type="submit">LÆ°u thĂ´ng tin sĂ¢n</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/yards/create.blade.php ENDPATH**/ ?>
