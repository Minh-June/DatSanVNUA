

<?php $__env->startSection('title', 'Thay Ã„â€˜Ã¡Â»â€¢i mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u'); ?>

<?php $__env->startSection('content'); ?>  
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <h3>Thay Ã„â€˜Ã¡Â»â€¢i mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u</h3> 

    <div class="adminedit">
        <form method="POST" action="<?php echo e(route('cap-nhat-mat-khau')); ?>">
            <?php echo csrf_field(); ?>
            <label>MÃ¡ÂºÂ­t khÃ¡ÂºÂ©u hiÃ¡Â»â€¡n tÃ¡ÂºÂ¡i:</label>
            <input type="password" name="matkhau_hientai" required><br><br>

            <label>NhÃ¡ÂºÂ­p mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u mÃ¡Â»â€ºi:</label>
            <input type="password" name="matkhau_moi" required><br><br>

            <label>XÄ‚Â¡c nhÃ¡ÂºÂ­n mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u mÃ¡Â»â€ºi:</label>
            <input type="password" name="xacnhan_matkhau" required><br><br>

            <button class="update-btn" type="submit">CÃ¡ÂºÂ­p nhÃ¡ÂºÂ­t mÃ¡ÂºÂ­t khÃ¡ÂºÂ©u</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/account/password.blade.php ENDPATH**/ ?>
