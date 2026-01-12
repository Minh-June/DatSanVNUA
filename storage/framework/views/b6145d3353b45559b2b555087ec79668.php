

<?php $__env->startSection('title', 'Danh sách size sản phẩm'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>
<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<h2>Quản lý size sản phẩm</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="<?php echo e(route('cap-nhat-san-pham', $product_id)); ?>">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="admin-add-btn">
        <?php if(auth()->user()->role != 3): ?>
            <a class="update-btn" href="<?php echo e(route('them-size', $product_id)); ?>">Thêm size mới</a>
        <?php endif; ?>
    </div>
</div>

<table id='ListCustomers'>
    <thead>
        <tr>
            <th>STT</th>
            <th>Tên size</th>
            <th>Giá tiền (đ)</th>
            <th>Số lượng</th>
            <?php if(auth()->user()->role != 3): ?>
                <th colspan="2">Tuỳ chọn</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($size->name); ?></td>
                <td><?php echo e(number_format($size->price, 0, '', '.')); ?>đ</td>
                <td><?php echo e($size->quantity); ?></td>

                <?php if(auth()->user()->role != 3): ?>
                    <td>
                        <form action="<?php echo e(route('cap-nhat-size', [$product_id, $size->product_size_id])); ?>" method="GET">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>

                    <td>
                        <form action="<?php echo e(route('xoa-size', [$product_id, $size->product_size_id])); ?>" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa size này?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="<?php echo e(auth()->user()->role != 3 ? 6 : 4); ?>" style="text-align:center;">Chưa có size nào</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/sizes/index.blade.php ENDPATH**/ ?>