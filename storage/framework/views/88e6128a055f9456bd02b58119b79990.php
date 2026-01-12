

<?php $__env->startSection('title', 'Quản lý khung giờ sân'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>

<h2><?php echo e($yard->type->name ?? 'Loại sân không xác định'); ?> - <?php echo e($yard->name ?? 'Không xác định'); ?></h2>

<?php
    $user = auth()->user();
    $owner = $yard->user ?? null;
    $canManage = false;

    if($owner) {
        if($user->role == 0 && $owner->role == 0 && $owner->user_id == $user->user_id) {
            // Admin xem sân do chính họ quản lý
            $canManage = true;
        } elseif($user->role == 2 && $owner->user_id == $user->user_id) {
            // Chủ sân xem sân của mình
            $canManage = true;
        }
        // Nhân viên role=3 luôn ẩn cột
    }
?>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="<?php echo e(route('quan-ly-san')); ?>">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <?php if($canManage): ?>
    <div class="admin-add-btn">
        <a class="update-btn" href="<?php echo e(route('them-thoi-gian-san', ['yard_id' => $yard->yard_id])); ?>">
            Thêm khung giờ cho thuê
        </a>
    </div>
    <?php endif; ?>
</div>

<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Khung giờ</th>
            <th>Giá T2-T6 (đ)</th>
            <th>Giá T7-CN (đ)</th>
            <?php if($canManage): ?>
                <th colspan="3">Tùy chọn</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $times; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($time->start)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($time->end)->format('H:i')); ?></td>
                <td>
                    <?php echo e(($time->price_weekday && $time->price_weekday > 0) 
                        ? number_format($time->price_weekday, 0, ',', '.') . 'đ' 
                        : 'Không cho thuê'); ?>

                </td>
                <td>
                    <?php echo e(($time->price_weekend && $time->price_weekend > 0) 
                        ? number_format($time->price_weekend, 0, ',', '.') . 'đ' 
                        : 'Không cho thuê'); ?>

                </td>

                <?php if($canManage): ?>
                    <td>
                        <form method="POST" action="<?php echo e(route('cap-nhat-trang-thai-thoi-gian-dat-san', ['_id' => $time->time_id])); ?>">
                            <?php echo csrf_field(); ?>
                            <select name="status">
                                <option value="0" <?php echo e($time->status == 0 ? 'selected' : ''); ?>>Hiển thị</option>
                                <option value="1" <?php echo e($time->status == 1 ? 'selected' : ''); ?>>Ẩn</option>
                            </select><br>
                            <button type="submit" class="update-btn">Cập nhật</button>
                        </form>
                    </td>
                    <td>
                        <form method="GET" action="<?php echo e(route('cap-nhat-thoi-gian-san', ['time_id' => $time->time_id])); ?>">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('xoa-thoi-gian-san', ['time_id' => $time->time_id])); ?>"
                              onsubmit="return confirm('Bạn có chắc chắn muốn xoá khung giờ này?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="<?php echo e($canManage ? 7 : 4); ?>" style="text-align:center;">
                    Chưa có khung giờ nào.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/timeyards/index.blade.php ENDPATH**/ ?>