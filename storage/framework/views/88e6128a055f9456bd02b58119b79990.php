

<?php $__env->startSection('title', 'Quản lý khung giờ cho thuê'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            alert("<?php echo e(session('error')); ?>");
        </script>
    <?php endif; ?>

    <h2><?php echo e($yard->type->name ?? 'Loại sân không xác định'); ?> - <?php echo e($yard->name ?? 'Không xác định'); ?></h2>

    <div class="admin-top-bar">
        <?php if(request('yard_id')): ?>
            <div class="admin-search">
                <form method="GET" action="<?php echo e(route('quan-ly-thoi-gian-san')); ?>">
                    <input type="hidden" name="yard_id" value="<?php echo e(request('yard_id')); ?>">
                    <label for="date">Ngày:</label>
                    <input type="date" id="date" name="date" value="<?php echo e(request('date', date('Y-m-d'))); ?>">
                    <button class="update-btn" type="submit">Tìm kiếm</button>
                </form>
            </div>
        <?php endif; ?>

        <?php if(!$isPastDate): ?>
            <div class="admin-add-btn">
                <a class="update-btn"
                    href="<?php echo e(route('them-thoi-gian-san', ['yard_id' => request('yard_id'), 'date' => $date])); ?>">
                    Thêm khung giờ
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Hiển thị bảng dữ liệu khi đã chọn sân và lọc theo ngày -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Khung giờ</th>
                <th>Giá tiền</th>
                <?php if(!$isPastDate): ?>
                    <th colspan="2">Tuỳ chọn</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $times; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($time->time); ?></td>
                    <td><?php echo e(number_format($time->price, 0, ',', '.')); ?>đ</td>

                    <?php if(!$isPastDate): ?>
                        <td>
                            <form method="GET" action="<?php echo e(route('cap-nhat-thoi-gian-san', ['time_id' => $time->time_id])); ?>">
                                <button type="submit" class="update-btn">Sửa</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST"
                                action="<?php echo e(route('xoa-thoi-gian-san', ['time_id' => $time->time_id, 'yard_id' => request('yard_id')])); ?>"
                                onsubmit="return confirm('Bạn có chắc chắn muốn xoá khung giờ này?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/timeyards/index.blade.php ENDPATH**/ ?>