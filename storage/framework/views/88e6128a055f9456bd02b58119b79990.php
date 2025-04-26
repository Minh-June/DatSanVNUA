

<?php $__env->startSection('title', 'Quản lý thời gian thuê sân'); ?>

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

    <h3>Thời gian thuê sân</h3>
    
    <!-- Begin: Form chọn sân -->
    <div class="admin-time">
        <form method="POST" action="<?php echo e(route('search.time_slots')); ?>">
            <?php echo csrf_field(); ?>
            <label for="san_id">Chọn sân:</label>
            <select class="admin-time-select" name="san_id" id="san_id">
                <?php $__currentLoopData = $san_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $san): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($san->san_id); ?>" <?php echo e((isset($selected_san_id) && $selected_san_id == $san->san_id) ? 'selected' : ''); ?>>
                        <?php echo e($san->tensan); ?> - <?php echo e($san->sosan); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="admin-time-btn" type="submit" name="search_time_slots">Tìm kiếm</button>
        </form>
    </div>
    <!-- End: Form chọn sân -->

    <!-- Begin: Table danh sách khung giờ -->
    <?php if(isset($time_slots)): ?>
        <?php if($time_slots->count() > 0): ?>
            <table id='ListCustomers'>
                <tr>
                    <th>STT</th>
                    <th>Khung giờ</th>
                    <th>Giá tiền</th>
                    <th>Cập nhật</th>
                    <th>Xóa</th>
                </tr>
                <?php $__currentLoopData = $time_slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($slot->time_slot); ?></td>
                    <td><?php echo e($slot->price); ?></td>
                    <td>
                        <form method="GET" action="<?php echo e(route('cap-nhat-thoi-gian-san', $slot->time_slot_id)); ?>">
                            <button type="submit">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('delete-time-slot', $slot->time_slot_id)); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xóa khung giờ này?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit">Xóa</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        <?php else: ?>
            <p>Không có khung giờ nào được tìm thấy cho sân này.</p>
        <?php endif; ?>
    <?php endif; ?>
    <!-- End: Table danh sách khung giờ -->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/timeyards/index.blade.php ENDPATH**/ ?>