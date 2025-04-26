

<?php $__env->startSection('title', 'Danh sách sân'); ?>

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

    <div class="admin-section">
        <h3>Danh sách các sân đang có</h3>

        <!-- Hiển thị bảng dữ liệu -->
        <table id='ListCustomers'>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên sân</th>
                    <th>Số sân</th>
                    <th>Cập nhật</th>
                    <th>Xóa</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($key + 1); ?></td>
                        <td><?php echo e($yard->tensan); ?></td>
                        <td><?php echo e($yard->sosan); ?></td>
                        <td>
                            <form method="GET" action="<?php echo e(route('cap-nhat-san', ['san_id' => $yard->san_id])); ?>">
                                <button type="submit">Sửa</button>
                            </form>
                        </td>                                      
                        <td>
                            <form method="POST" action="<?php echo e(route('delete-yard', $yard->san_id)); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sân này không?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit">Xóa</button>
                            </form>
                        </td>                                                                           
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/yards/index.blade.php ENDPATH**/ ?>