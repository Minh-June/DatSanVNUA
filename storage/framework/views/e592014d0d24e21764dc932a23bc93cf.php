

<?php $__env->startSection('title', 'Quản lý loại sản phẩm'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>
<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<h2>Quản lý loại sản phẩm</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="<?php echo e(route('quan-ly-cua-hang')); ?>">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="admin-add-btn">
        <?php if(auth()->user()->role != 3): ?>
            <a class="update-btn" href="<?php echo e(route('them-loai-san-pham', $store->store_id)); ?>">Thêm loại sản phẩm</a>
        <?php endif; ?>
    </div>
</div>

<table id='ListCustomers'>
    <thead>
        <tr>
            <th>STT</th>
            <th>Loại sản phẩm</th>
            <?php if(auth()->user()->role != 3): ?>
                <th colspan="2">Tùy chọn</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($type->name); ?></td>

                <?php if(auth()->user()->role != 3): ?>
                    <td>
                        <form action="<?php echo e(route('cap-nhat-loai-san-pham', $type->product_type_id)); ?>" method="GET">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>

                    <td>
                        <form action="<?php echo e(route('xoa-loai-san-pham', $type->product_type_id)); ?>" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa loại sản phẩm này ?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="<?php echo e(auth()->user()->role != 3 ? 4 : 2); ?>" style="text-align:center;">
                    Hiện cửa hàng chưa có loại sản phẩm nào
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/producttypes/index.blade.php ENDPATH**/ ?>