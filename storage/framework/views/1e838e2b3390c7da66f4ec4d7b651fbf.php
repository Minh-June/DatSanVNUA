

<?php $__env->startSection('title', 'Thông tin cá nhân'); ?>

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

                        <h3>Thông tin cá nhân</h3>

                        <?php if(session('success')): ?>
                            <script>
                                alert('<?php echo e(session('success')); ?>');
                            </script>
                        <?php endif; ?>

                        <!-- Form hiển thị thông tin cá nhân -->
                        <div class="adminedit">
                            <form method="post" action="<?php echo e(route('cap-nhat-thong-tin')); ?>">
                                <?php echo csrf_field(); ?> <!-- Thêm token CSRF -->
                                <!-- <label for="fullname">Họ và tên:</label>
                                <input type="text" name="fullname" value="<?php echo e($user->fullname); ?>" required><br> -->
                                
                                <label for="gender">Giới tính:</label>
                                <select class="admin-time-select" name="gender" required>
                                    <option value="Nam" <?php echo e($user->gender == 'Nam' ? 'selected' : ''); ?>>Nam</option>
                                    <option value="Nữ" <?php echo e($user->gender == 'Nữ' ? 'selected' : ''); ?>>Nữ</option>
                                    <option value="Khác" <?php echo e($user->gender == 'Khác' ? 'selected' : ''); ?>>Khác</option>
                                </select><br>
                                
                                <label for="birthdate">Ngày sinh:</label>
                                <input type="date" name="birthdate" value="<?php echo e($user->birthdate); ?>" required><br>
                                
                                <label for="phonenb">Số điện thoại:</label>
                                <input type="text" name="phonenb" value="<?php echo e($user->phonenb); ?>" required><br>
                                
                                <label for="email">Email:</label>
                                <input type="email" name="email" value="<?php echo e($user->email); ?>" required><br>
                                
                                <button class="update-btn" type="submit">Cập nhật thông tin cá nhân</button>
                            </form>                          

                        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.client.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/user/infor.blade.php ENDPATH**/ ?>