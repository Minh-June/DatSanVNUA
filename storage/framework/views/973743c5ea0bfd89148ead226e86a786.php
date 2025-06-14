

<?php $__env->startSection('title', 'Quản lý hình ảnh sân'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo thành công -->
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <h2>Quản lý hình ảnh - <?php echo e($selectedYard->name); ?></h2>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            <a class="update-btn" href="<?php echo e(isset($selectedYard) ? route('them-hinh-anh-san', ['yard_id' => $selectedYard->yard_id]) : route('them-hinh-anh-san')); ?>">
                Thêm hình ảnh
            </a>
        </div>
    </div>

    <!-- Hiển thị bảng hình ảnh khi đã chọn sân -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Hình ảnh</th>
                <th colspan="2">Tùy chọn</th>
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
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/imgyards/index.blade.php ENDPATH**/ ?>