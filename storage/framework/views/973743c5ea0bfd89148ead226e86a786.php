

<?php $__env->startSection('title', 'Quản lý hình ảnh sân'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>
<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<?php if($selectedYard): ?>
    <h2><?php echo e($selectedYard->type->name ?? 'Loại sân không xác định'); ?> - <?php echo e($selectedYard->name ?? 'Không xác định'); ?></h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <a class="update-btn" href="<?php echo e(route('quan-ly-san')); ?>">
                <i class="fa-solid fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <?php if($canManage): ?>
            <div class="admin-add-btn">
                <a class="update-btn" href="<?php echo e(route('them-hinh-anh-san', ['yard_id' => $selectedYard->yard_id])); ?>">
                    Thêm hình ảnh
                </a>
            </div>
        <?php endif; ?>
    </div>

    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Hình ảnh</th>
                <?php if($canManage): ?>
                    <th colspan="2">Tùy chọn</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $selectedYard->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td>
                        <img src="<?php echo e(asset('storage/' . $image->image)); ?>" 
                             alt="Hình ảnh" 
                             class="football-img"
                             onclick="showImage(this.src)">
                    </td>

                    <?php if($canManage): ?>
                        <td>
                            <form action="<?php echo e(route('cap-nhat-hinh-anh-san', ['image_id' => $image->image_id])); ?>" method="GET">
                                <button type="submit" class="update-btn">Sửa</button>
                            </form>
                        </td>
                        <td>
                            <form action="<?php echo e(route('xoa-hinh-anh-san', ['image_id' => $image->image_id])); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hình ảnh này?');">
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
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/imgyards/index.blade.php ENDPATH**/ ?>