

<?php $__env->startSection('title', 'Quản lý hình ảnh sân'); ?>

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

    <h3>Danh sách hình ảnh sân</h3>

    <!-- Hiển thị bảng dữ liệu -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sân</th>
                <th>Số sân</th>
                <th>Hình ảnh</th>
                <th>Cập nhật</th>
                <th>Xóa</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $sans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $san): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($san->images->isEmpty()): ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($san->tensan); ?></td>
                        <td><?php echo e($san->sosan); ?></td>
                        <td colspan="3">Chưa có ảnh sân</td>
                    </tr>
                <?php else: ?>
                    <?php $__currentLoopData = $san->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <!-- Sử dụng $loop->parent để lấy chỉ số của sân -->
                            <td><?php echo e($loop->parent->iteration); ?></td>
                            <td><?php echo e($san->tensan); ?></td>
                            <td><?php echo e($san->sosan); ?></td>
                            <td>
                                <img src="<?php echo e(asset(Storage::url($image->image))); ?>" alt="Hình ảnh" class="admin-image">
                            </td>
                            <td>
                                <form action="<?php echo e(route('sua-hinh-anh-san', ['image_id' => $image->image_id])); ?>" method="GET">
                                    <button type="submit" class="btn btn-primary">Sửa</button>
                                </form>
                            </td>                            
                            <td>
                                <form action="<?php echo e(route('xoa-hinh-anh-san', ['image_id' => $image->image_id])); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa hình ảnh này?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger">Xóa</button>
                                </form>                                
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/imgyards/index.blade.php ENDPATH**/ ?>