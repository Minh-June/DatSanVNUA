

<?php $__env->startSection('title', 'Quản lý cửa hàng thể thao'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>
<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<?php
    $user = auth()->user();
?>

<h2>Quản lý cửa hàng</h2>

<div class="admin-top-bar">
    <div class="admin-search"></div>
    
    <div class="admin-add-btn">
        <?php if($user->role == 2): ?>
            <?php
                // Kiểm tra user hiện tại đã có cửa hàng chưa
                $hasStore = $stores->where('user_id', $user->user_id)->isNotEmpty();
            ?>

            <?php if(!$hasStore): ?>
                <a class="update-btn" href="<?php echo e(route('them-cua-hang')); ?>">Thêm cửa hàng mới</a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<table id='ListCustomers'>
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên cửa hàng</th>
            <th>Chủ sở hữu</th>
            <th>Số điện thoại</th>
            <th colspan="2">Thông tin</th>
            
            <?php if($user->role != 3): ?>
                <th colspan="3">Tùy chọn</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php if($stores->isEmpty()): ?>
            <tr>
                <td colspan="<?php echo e($user->role != 3 ? 7 : 4); ?>" style="text-align:center;">Hiện chưa có cửa hàng nào</td>
            </tr>
        <?php else: ?>
            <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($store->name); ?></td>
                    <td><?php echo e($store->user->fullname ?? '-'); ?></td>
                    <td><?php echo e($store->user->phonenb ?? '-'); ?></td>
                    <td>
                        <a href="<?php echo e(route('quan-ly-loai-san-pham', [$store->store_id])); ?>">Loại sản phẩm</a><br>
                    </td>
                    <td>
                        <a href="<?php echo e(route('quan-ly-san-pham', [$store->store_id])); ?>">Sản phẩm</a>
                    </td>

                    
                    <?php if($user->role != 3): ?>
                        <td>
                            <form action="<?php echo e(route('cap-nhat-trang-thai-cua-hang', $store->store_id)); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <select name="status">
                                    <option value="0" <?php echo e($store->status == 0 ? 'selected' : ''); ?>>Hoạt động</option>
                                    <option value="1" <?php echo e($store->status == 1 ? 'selected' : ''); ?>>Đã Ẩn</option>
                                </select><br>
                                <button type="submit" class="update-btn">Cập nhật</button>
                            </form>
                        </td>
                        <?php if(in_array($user->role, [0,2])): ?>
                            <td>
                                <form method="GET" action="<?php echo e(route('cap-nhat-thong-tin-cua-hang', $store->store_id)); ?>">
                                    <button type="submit" class="update-btn">Sửa</button>
                                </form>
                            </td>
                            <td>
                                <form method="POST" action="<?php echo e(route('xoa-cua-hang', $store->store_id)); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xoá cửa hàng này không?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="delete-btn">Xóa</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    <?php endif; ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </tbody>
</table>

<?php
    $user = auth()->user();
?>

<?php if($user->role == 2): ?>
    <h2>Danh sách nhân viên</h2>

    <div class="admin-top-bar">
        <div class="admin-search"></div>
        <div class="admin-add-btn"></div>
    </div>

    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên nhân viên</th>
                <th>Ngày sinh</th>
                <th>Số điện thoại</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($emp->fullname); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($emp->birthdate)->format('d/m/Y')); ?></td>
                    <td><?php echo e($emp->phonenb); ?></td>
                    <td><?php echo e($emp->email); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Cửa hàng chưa có nhân viên nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/stores/index.blade.php ENDPATH**/ ?>