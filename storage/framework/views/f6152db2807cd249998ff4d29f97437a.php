

<?php $__env->startSection('title', 'Danh sách sân'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo -->
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
    
    <h3>Danh sách sân thể thao</h3>

    <!-- Form tìm kiếm loại sân và thêm sân mới -->
    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="<?php echo e(route('quan-ly-san')); ?>">
                <label for="type_id">Chọn loại sân:</label>
                <select id="type_id" name="type_id">
                    <option value="">Tất cả</option>
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->type_id); ?>" 
                            <?php echo e(request('type_id') == $type->type_id ? 'selected' : ''); ?>><?php echo e($type->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="admin-search-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a href="<?php echo e(route('them-san')); ?>">Thêm sân mới</a>
        </div>
    </div>

    <!-- Hiển thị bảng dữ liệu -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sân</th>
                <th colspan="2">Thông tin</th>
                <th colspan="2">Tuỳ chọn</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key + 1); ?></td>
                    <td><?php echo e($yard->name); ?></td>
                <td>
                    <a href="<?php echo e(route('quan-ly-thoi-gian-san', ['yard_id' => $yard->yard_id])); ?>">Thời gian</a><br>
                </td>
                <td>
                    <a href="<?php echo e(route('quan-ly-hinh-anh-san', ['yard_id' => $yard->yard_id])); ?>">Hình ảnh</a>
                </td>
                    <td>
                        <form method="GET" action="<?php echo e(route('cap-nhat-san', ['yard_id' => $yard->yard_id])); ?>">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>                                      
                    <td>
                        <form method="POST" action="<?php echo e(route('xoa-san', ['yard_id' => $yard->yard_id, 'type_id' => request('type_id')])); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xoá sân này không?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="update-btn">Xóa</button>
                        </form>
                    </td>                                                                           
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/yards/index.blade.php ENDPATH**/ ?>