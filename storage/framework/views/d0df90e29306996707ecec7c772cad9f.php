

<?php $__env->startSection('title', 'Danh sách tin tức'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <h2>Danh sách bài đăng tin tức</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="<?php echo e(route('quan-ly-tin-tuc')); ?>">
                <!-- Input chọn ngày -->
                <input type="date" name="date" value="<?php echo e(request('date', $date)); ?>">
                
                <!-- Input từ khóa -->
                <input type="text" name="search" placeholder="Tìm kiếm tin tức..." value="<?php echo e(request('search')); ?>">

                <button class="update-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>
            
        <div class="admin-add-btn">
            <a style='margin-right: 10px;' class="update-btn" href="<?php echo e(route('them-tin-tuc')); ?>">Đăng tin tức mới</a>
            <a class="update-btn" href="<?php echo e(route('quan-ly-loai-tin-tuc')); ?>">Danh sách loại tin tức</a>
        </div>
    </div>

    <table id="ListCustomers">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ngày đăng</th>
                <th>Người đăng</th>
                <th>Số điện thoại</th>
                <th>Loại tin</th>
                <th>Tiêu đề</th>
                <th>Thông tin</th>
                <?php if(auth()->user()->role != 3): ?>
                    <th colspan='2'>Tuỳ chọn</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $newsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($loop->iteration); ?></td>
                <td><?php echo e($news->post_at ? \Carbon\Carbon::parse($news->post_at)->format('d/m/Y') : ''); ?></td>
                <td class="left-align">
                    <?php
                        $fullname = $news->user ? $news->user->fullname : 'Chưa xác định';
                        $words = explode(' ', $fullname); 
                        $chunks = array_chunk($words, 4); 
                    ?>

                    <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(implode(' ', $chunk)); ?><br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td><?php echo e($news->user ? $news->user->phonenb : 'Chưa xác định'); ?></td>
                <td><?php echo e($news->type ? $news->type->name : 'Chưa xác định'); ?></td>
                <td class="left-align">
                    <?php
                        $words = explode(' ', $news->title); 
                        $chunks = array_chunk($words, 5); 
                    ?>

                    <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(implode(' ', $chunk)); ?><br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td>
                    <a href="<?php echo e(route('cap-nhat-tin-tuc', $news->news_id)); ?>">
                        Nội dung
                    </a>
                </td>

                <?php if(auth()->user()->role != 3): ?>
                    <?php if(auth()->user()->role == 0 && $news->user && in_array($news->user->role, [2,3])): ?>
                        <td colspan="2" style="text-align:center;">Đối tác đăng</td>
                    <?php else: ?>
                        
                        <td>
                            <form method="POST" action="<?php echo e(route('cap-nhat-trang-thai-tin-tuc')); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="news_id" value="<?php echo e($news->news_id); ?>">
                                <select name="status">
                                    <option value="0" <?php echo e($news->status == 0 ? 'selected' : ''); ?>>Đang hiện</option>
                                    <option value="1" <?php echo e($news->status == 1 ? 'selected' : ''); ?>>Đã ẩn</option>
                                </select><br>
                                <button type="submit" class="update-btn">Cập nhật</button>
                            </form>
                        </td>
                        
                        <td>
                            <form action="<?php echo e(route('xoa-tin-tuc', $news->news_id)); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    <?php endif; ?>
                <?php endif; ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="<?php echo e(auth()->user()->role == 3 ? 7 : 9); ?>" style="text-align:center;">Chưa có tin tức nào</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/news/index.blade.php ENDPATH**/ ?>