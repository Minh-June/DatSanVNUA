

<?php $__env->startSection('title', 'Thay đổi mật khẩu'); ?>

<?php $__env->startSection('content'); ?>  
    <!-- Hiển thị thông báo thành công -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <!-- Hiển thị thông báo lỗi -->
    <?php if(session('error')): ?>
        <script>
            alert("<?php echo e(session('error')); ?>");
        </script>
    <?php endif; ?>

                        <h3>Thay đổi mật khẩu</h3> 

                        <div class="adminedit">
                            <form method="post" action="<?php echo e(route('thay-doi-mat-khau')); ?>">
                                <?php echo csrf_field(); ?>
                                <label for="matkhau_hientai">Mật khẩu hiện tại:</label>
                                <input type="password" name="matkhau_hientai" required><br><br>
                            
                                <label for="matkhau_moi">Mật khẩu mới:</label>
                                <input type="password" name="matkhau_moi" required><br><br>
                            
                                <label for="xacnhan_matkhau">Xác nhận mật khẩu mới:</label>
                                <input type="password" name="xacnhan_matkhau" required><br><br>
                            
                                <button class="update-btn" type="submit">Cập nhật mật khẩu mới</button>
                            </form>                            
                        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.client.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/user/password.blade.php ENDPATH**/ ?>