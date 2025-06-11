

<?php $__env->startSection('title', 'SÃ¡Â»Â­a thÄ‚Â´ng tin sÄ‚Â¢n'); ?>

<?php $__env->startSection('content'); ?>
    <!-- HiÃ¡Â»Æ’n thÃ¡Â»â€¹ thÄ‚Â´ng bÄ‚Â¡o -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <!-- HiÃ¡Â»Æ’n thÃ¡Â»â€¹ thÄ‚Â´ng bÄ‚Â¡o lÃ¡Â»â€”i -->
    <?php if(session('error')): ?>
        <script>
            alert("<?php echo e(session('error')); ?>");
        </script>
    <?php endif; ?>

    <h3>SÃ¡Â»Â­a thÄ‚Â´ng tin sÄ‚Â¢n</h3>

    <!-- Form chÃ¡Â»â€°nh sÃ¡Â»Â­a thÄ‚Â´ng tin sÄ‚Â¢n -->
    <div class="adminedit">
        <form method="POST" action="<?php echo e(route('update.yard', $yard->yard_id)); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="POST">

            <label for="type_id">ThÃ¡Â»Æ’ loÃ¡ÂºÂ¡i sÄ‚Â¢n:</label>
            <select id="type_id" name="type_id" required>
                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->type_id); ?>" <?php echo e($yard->type_id == $type->type_id ? 'selected' : ''); ?>>
                        <?php echo e($type->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <br>

            <label for="name">TÄ‚Âªn sÄ‚Â¢n:</label>
            <input type="text" id="name" name="name" value="<?php echo e(old('name', $yard->name)); ?>" required>
            <br>

            <button class="update-btn" type="submit">CÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t thÄ‚Â´ng tin sÄ‚Â¢n</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/yards/update.blade.php ENDPATH**/ ?>
