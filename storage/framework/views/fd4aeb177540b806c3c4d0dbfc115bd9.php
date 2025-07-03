

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
    
    <h2>Danh sách sân thể thao</h2>

    <!-- Form tìm kiếm loại sân và thêm sân mới -->
    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="<?php echo e(route('quan-ly-san')); ?>">
                <select id="type_id" name="type_id">
                    <option value="">Chọn loại sân</option>
                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->type_id); ?>" 
                            <?php echo e(request('type_id') == $type->type_id ? 'selected' : ''); ?>><?php echo e($type->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="update-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a class="update-btn" href="<?php echo e(route('them-san')); ?>">Thêm sân mới</a>
        </div>
    </div>

    <!-- Hiển thị bảng dữ liệu -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Loại sân</th>
                <th>Tên sân</th>
                <th colspan="2">Thông tin</th>
                <th colspan="3">Tuỳ chọn</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $index = 0;
                // Nhóm sân theo loại sân (type name)
                $yardsGrouped = $yardsGrouped = $yards->groupBy(fn($yard) => $yard->type->name ?? 'Không tồn tại');
            ?>

            <?php $__currentLoopData = $yardsGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeName => $yardsOfType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $count = $yardsOfType->count();
                ?>
                <?php $__currentLoopData = $yardsOfType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e(++$index); ?></td>
                        
                        <?php if($key == 0): ?>
                            <td class="left-align" rowspan="<?php echo e($count); ?>"><?php echo e($typeName); ?></td>
                        <?php endif; ?>
                        <td class="left-align"><?php echo e($yard->name); ?></td>
                        <td>
                            <a href="<?php echo e(route('quan-ly-thoi-gian-san', ['yard_id' => $yard->yard_id, 'type_id' => request('type_id')])); ?>">
                                Thời gian
                            </a><br>
                        </td>
                        <td>
                            <a href="<?php echo e(route('quan-ly-hinh-anh-san', ['yard_id' => $yard->yard_id, 'type_id' => request('type_id')])); ?>">
                                Hình ảnh
                            </a>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo e(route('cap-nhat-trang-thai-san')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="yard_id" value="<?php echo e($yard->yard_id); ?>">
                                <select name="status">
                                    <option value="0" <?php echo e($yard->status == 0 ? 'selected' : ''); ?>>Đang hiện</option>
                                    <option value="1" <?php echo e($yard->status == 1 ? 'selected' : ''); ?>>Đã ẩn</option>
                                </select><br>
                                <button type="submit" class="update-btn">Cập nhật</button>
                            </form>
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
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>                                                                           
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/yards/index.blade.php ENDPATH**/ ?>