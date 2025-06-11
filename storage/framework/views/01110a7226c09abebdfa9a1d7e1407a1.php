

<?php $__env->startSection('title', 'ThÄ‚Âªm loÃ¡ÂºÂ¡i sÄ‚Â¢n'); ?>

<?php $__env->startSection('content'); ?>
    <!-- HiÃ¡Â»Æ’n thÃ¡Â»â€¹ thÄ‚Â´ng bÄ‚Â¡o -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <!-- HiÃ¡Â»Æ’n thÃ¡Â»â€¹ thÄ‚Â´ng bÄ‚Â¡o lÃ¡Â»â€”i -->
    <?php if($errors->any()): ?>
        <script>
            alert("<?php echo e($errors->first()); ?>");
        </script>
    <?php endif; ?>

    <h3>ThÄ‚Âªm loÃ¡ÂºÂ¡i sÄ‚Â¢n</h3>

    <!-- Form thÄ‚Âªm loÃ¡ÂºÂ¡i sÄ‚Â¢n mÃ¡Â»â€ºi -->
    <div class="adminedit">
        <form action="<?php echo e(route('luu-loai-san')); ?>" method="POST">
            <?php echo csrf_field(); ?> <!-- ThÄ‚Âªm CSRF token -->
            <label for="name">TÄ‚Âªn loÃ¡ÂºÂ¡i sÄ‚Â¢n:</label>
            <input type="text" id="name" name="name" required>
            <br>
            <button class="update-btn" type="submit">LÃ†Â°u thÄ‚Â´ng tin loÃ¡ÂºÂ¡i sÄ‚Â¢n</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/types/create.blade.php ENDPATH**/ ?>
