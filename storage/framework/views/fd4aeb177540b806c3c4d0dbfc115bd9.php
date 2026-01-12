

<?php $__env->startSection('title', 'Danh sách sân'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo -->
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

        <?php if(auth()->user()->role != 3 && auth()->user()->role != 2): ?> 
            <div class="admin-add-btn">
                <a class="update-btn" href="<?php echo e(route('them-san')); ?>">Thêm sân mới</a>
            </div>
        <?php endif; ?>
    </div>

    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>Loại sân</th>
                <th>Tên sân</th>
                <?php if(auth()->user() && auth()->user()->role == 0): ?>
                    <th colspan="3">Thông tin</th>
                <?php else: ?>
                    <th colspan="2">Thông tin</th>
                <?php endif; ?>
                <?php if(auth()->user()->role != 3): ?>
                    <th colspan="3">Tuỳ chọn</th> 
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
                $index = 0;
                $yardsGrouped = $yards->groupBy(fn($yard) => $yard->type->name ?? 'Không tồn tại');
            ?>

            <?php $__currentLoopData = $yardsGrouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeName => $yardsOfType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $count = $yardsOfType->count();
                ?>
                <?php $__currentLoopData = $yardsOfType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isBlocked = $yard->user && $yard->user->role == 2; // sân đã phân cho role=2
                    ?>
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
                        <?php if(auth()->user()->role == 0): ?>
                            <td>
                                <a href="<?php echo e(route('thong-tin-don-vi-thau')); ?>?yard_id=<?php echo e($yard->yard_id); ?>&user_id=<?php echo e($yard->user->user_id ?? \App\Models\User::where('role', 0)->first()->user_id); ?>">
                                    Đơn vị thầu
                                </a>
                            </td>
                        <?php endif; ?>
                        
                        <?php
                            $hideForUser = in_array(auth()->user()->role, [2,3]);
                            $isAdminBlocked = auth()->user()->role == 0 && $yard->user && $yard->user->role == 2;
                        ?>

                        
                        <?php if(auth()->user()->role != 3): ?>
                            <?php if($isAdminBlocked): ?>
                                <td colspan="3" style="color:var(--primary-color);">
                                    Đang khai thác (bởi đối tác)
                                </td>
                            <?php else: ?>
                                
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

                                
                                <?php if(!$hideForUser): ?>
                                <td>
                                    <form method="GET" action="<?php echo e(route('cap-nhat-san', ['yard_id' => $yard->yard_id])); ?>">
                                        <button type="submit" class="update-btn">Sửa</button>
                                    </form>
                                </td>
                                <?php endif; ?>

                                
                                <?php if(!$hideForUser): ?>
                                <td>
                                    <form method="POST" action="<?php echo e(route('xoa-san', ['yard_id' => $yard->yard_id, 'type_id' => request('type_id')])); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xoá sân này không?')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="delete-btn">Xóa</button>
                                    </form>
                                </td>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/yards/index.blade.php ENDPATH**/ ?>