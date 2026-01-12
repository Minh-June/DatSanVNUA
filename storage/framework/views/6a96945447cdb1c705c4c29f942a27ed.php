

<?php $__env->startSection('title', 'Quản lý sản phẩm'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>
<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<h2>Quản lý sản phẩm</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <form method="GET" action="<?php echo e(route('quan-ly-san-pham', $store->store_id)); ?>">
            <input type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo e(request('search')); ?>">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>

    <div class="admin-add-btn">
        <a class="update-btn" href="<?php echo e(route('them-san-pham', $store->store_id)); ?>">Thêm sản phẩm</a>
    </div>
</div>

<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Loại SP</th>
            <th colspan="2">Sản phẩm</th>
            <th>Giá (đ)</th>
            <th>Tồn kho</th>
            <th>Thông tin</th>
            <?php if(auth()->user()->role != 3): ?>
                <th colspan="3">Tùy chọn</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td class="left-align"><?php echo e($product->type->name ?? 'Chưa có loại'); ?></td>
                <td>
                    <?php if($product->images->first() && $product->images->first()->image): ?>
                        <img
                            src="<?php echo e(asset('storage/' . $product->images->first()->image)); ?>"
                            alt="Ảnh sản phẩm"
                            style="width:80px;"
                        >
                    <?php else: ?>
                        Không có ảnh
                    <?php endif; ?>
                </td>
                <td class="left-align">
                    <?php
                        $words = explode(' ', $product->name);
                        $chunks = array_chunk($words, 9);
                    ?>
                    <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(implode(' ', $chunk)); ?><br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td>
                    <?php if($product->product_size_id): ?>
                        Có nhiều size
                    <?php else: ?>
                        <?php echo e($product->price ? number_format($product->price, 0, ',', '.') . 'đ' : 'Chưa có giá'); ?>

                    <?php endif; ?>
                </td>
                <td>
                    <?php if($product->product_size_id): ?>
                        <?php echo e($product->sizes->sum('quantity')); ?>

                    <?php else: ?>
                        <?php echo e($product->quantity ?? 0); ?>

                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo e(route('cap-nhat-san-pham', $product->product_id)); ?>">Nội dung</a>
                </td>

                <?php if(auth()->user()->role != 3): ?>
                    
                    <td>
                        <form method="POST" action="<?php echo e(route('cap-nhat-trang-thai-san-pham', $product->product_id)); ?>">
                            <?php echo csrf_field(); ?>
                            <select name="status">
                                <option value="0" <?php echo e($product->status == 0 ? 'selected' : ''); ?>>Đang hiện</option>
                                <option value="1" <?php echo e($product->status == 1 ? 'selected' : ''); ?>>Đã ẩn</option>
                            </select><br>
                            <button type="submit" class="update-btn">Cập nhật</button>
                        </form>
                    </td>

                    
                    <td>
                        <form action="<?php echo e(route('xoa-san-pham', $product->product_id)); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này không ?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="<?php echo e(auth()->user()->role != 3 ? 11 : 6); ?>" style="text-align:center;">Hiện cửa hàng chưa có sản phẩm nào</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/products/index.blade.php ENDPATH**/ ?>