

<?php $__env->startSection('title', 'Danh sách loại tin tức'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Thông báo thành công -->
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <!-- Thông báo lỗi -->
    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <h2>Danh sách loại tin tức</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="<?php echo e(route('quan-ly-loai-tin-tuc')); ?>">
                <select id="type_id" name="type_id">
                    <option value="">Chọn loại tin tức</option>
                    <?php $__currentLoopData = $allTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->news_type_id); ?>" 
                            <?php echo e(request('type_id') == $type->news_type_id ? 'selected' : ''); ?>>
                            <?php echo e($type->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="update-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a class="update-btn" href="<?php echo e(route('them-loai-tin-tuc')); ?>">Thêm loại tin tức</a>
        </div>
    </div>

    <table id="ListCustomers">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên loại tin tức</th>
                <th colspan="2">Tuỳ chọn</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($key + 1); ?></td>
                    <td class="left-align"><?php echo e($type->name); ?></td>
                    <td>
                        <form method="GET" action="<?php echo e(route('cap-nhat-loai-tin-tuc', ['news_type_id' => $type->news_type_id])); ?>">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('xoa-loai-tin-tuc', $type->news_type_id)); ?>"
                            onsubmit="return confirm('Bạn có chắc chắn muốn xoá loại tin tức này không?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" style="text-align:center;">Chưa có loại tin tức nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/newstype/index.blade.php ENDPATH**/ ?>