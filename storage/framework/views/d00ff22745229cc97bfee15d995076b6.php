

<?php $__env->startSection('title', 'Danh sách người dùng'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <script>
            alert("<?php echo e($errors->first('keyword')); ?>");
        </script>
    <?php endif; ?>

    <h2><?php echo e(isset($xem_user) ? 'Thông tin người dùng' : 'Danh sách người dùng'); ?></h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <?php if(!isset($xem_user)): ?>
                <form method="GET" action="<?php echo e(route('quan-ly-nguoi-dung')); ?>">
                    <input
                        type="text"
                        name="keyword"
                        placeholder="Nhập thông tin"
                        value="<?php echo e(request('keyword')); ?>"
                        required
                        pattern="^[\p{L}0-9\s]+$"
                        title="Chỉ nhập chữ cái, số và khoảng trắng"
                    />
                    <button class="update-btn" type="submit">Tìm kiếm</button>
                </form>
            <?php endif; ?>
        </div>

        <div class="admin-add-btn">
            <?php if(isset($xem_user)): ?>
                <a class="delete-btn"
                href="<?php echo e(route('reset-mat-khau-nguoi-dung', ['user_id' => $xem_user->user_id])); ?>"
                onclick="return confirm('Bạn có chắc chắn muốn đặt lại mật khẩu người dùng này không?')">
                <i class="fa-solid fa-rotate-left"></i> Đặt lại mật khẩu
                </a>
            <?php else: ?>
                <a class="update-btn" href="<?php echo e(route('dang-ky')); ?>">Thêm người dùng</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if(isset($xem_user)): ?>
        <!-- Hiển thị thông tin người dùng -->
        <div class="adminedit">
            <form>
                <?php echo csrf_field(); ?>
                <div class="adminedit-form-group">
                    <label for="fullname">Họ và tên:</label>
                    <input type="text" name="fullname" value="<?php echo e($xem_user->fullname); ?>" disabled>
                </div>

                <div class="adminedit-form-group">
                    <label for="gender">Giới tính:</label>
                    <input type="text" name="gender" value="<?php echo e($xem_user->gender); ?>" disabled>
                </div>

                <div class="adminedit-form-group">
                    <label for="birthdate">Ngày sinh:</label>
                    <input type="date" name="birthdate" value="<?php echo e($xem_user->birthdate); ?>" disabled>
                </div>

                <div class="adminedit-form-group">
                    <label for="phonenb">Số điện thoại:</label>
                    <input type="text" name="phonenb" value="<?php echo e($xem_user->phonenb); ?>" disabled>
                </div>
                
                <div class="adminedit-form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" value="<?php echo e($xem_user->email); ?>" disabled>
                </div>
            </form>                          
        </div>
        <br>
    <?php else: ?>
        <!-- Hiển thị bảng dữ liệu -->
        <table id='ListCustomers'>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Họ và tên</th>
                    <th>SĐT</th>
                    <th>Tên tài khoản</th>
                    <th>Thông tin</th>
                    <th>Vai trò</th>
                    <th>Tuỳ chọn</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td class="left-align"><?php echo e($user->fullname); ?></td>
                    <td><?php echo e($user->phonenb); ?></td>
                    <td><?php echo e($user->username); ?></td>
                    <td>
                        <a href="<?php echo e(route('quan-ly-nguoi-dung', ['xem' => $user->user_id])); ?>">Xem chi tiết</a>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('cap-nhat-vai-tro-nguoi-dung', $user->user_id)); ?>">
                            <?php echo csrf_field(); ?>
                            <select name="role">
                                <option value="0" <?php echo e($user->role == 0 ? 'selected' : ''); ?>>Admin</option>
                                <option value="1" <?php echo e($user->role == 1 ? 'selected' : ''); ?>>Khách hàng</option>
                                <option value="2" <?php echo e($user->role == 2 ? 'selected' : ''); ?>>Cán bộ</option>
                            </select><br>
                            <button type="submit" class="update-btn">Cập nhật</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('xoa-nguoi-dung', ['user_id' => $user->user_id])); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xoá người dùng này không?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/users/index.blade.php ENDPATH**/ ?>