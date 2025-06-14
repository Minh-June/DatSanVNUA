

<?php $__env->startSection('title', 'Quản lý đơn đặt sân thể thao'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <h2>Danh sách đơn đặt sân</h2>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="<?php echo e(route('quan-ly-don-dat-san')); ?>">
                <input type="hidden" name="yard_name" value="<?php echo e(request('yard_name')); ?>">
                <label for="selected_date">Chọn ngày:</label>
                <input type="date" id="selected_date" name="selected_date" value="<?php echo e(request('selected_date', now()->toDateString())); ?>">
                <button class="update-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a class="update-btn" href="<?php echo e(route('trang-chu')); ?>">Thêm đơn đặt sân</a>
        </div>
    </div>

    <?php if($orders->count()): ?>
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>Ngày tạo</th>
                <th>Họ và tên</th>
                <th>SĐT</th>
                <th>Thành tiền</th>
                <th>Ảnh thanh toán</th>
                <th>Thông tin</th>
                <th colspan="2">Tùy chọn</th>
            </tr>

            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key + 1); ?></td>
                    <td>
                        <?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?><br>
                        <?php echo e(\Carbon\Carbon::parse($order->date)->format('H:i')); ?>

                    </td>
                    <td class="left-align"><?php echo e($order->name); ?></td>
                    <td><?php echo e($order->phone); ?></td>
                    <td>
                        <?php echo e(number_format($order->orderDetails->sum('price'), 0, ',', '.')); ?>đ
                    </td>
                    <td>
                        <?php $images = json_decode($order->image); ?>
                        <?php if($images && count($images) > 0): ?>
                            <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="Ảnh" style="width:100px; height:200px; cursor:pointer;" onclick="showImage(this.src)">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            Không có
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo e(route('cap-nhat-don-dat-san', $order->order_id)); ?>">Xem chi tiết</a>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('cap-nhat-trang-thai-don-dat-san', $order->order_id)); ?>">
                            <?php echo csrf_field(); ?>
                            <select name="status">
                                <option value="0" <?php echo e($order->status == 0 ? 'selected' : ''); ?>>Chờ xác nhận</option>
                                <option value="1" <?php echo e($order->status == 1 ? 'selected' : ''); ?>>Xác nhận</option>
                                <option value="2" <?php echo e($order->status == 2 ? 'selected' : ''); ?>>Hủy</option>
                            </select><br>
                            <button type="submit" class="update-btn">Cập nhật</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('xoa-don-dat-san', $order->order_id)); ?>" onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    <?php else: ?>
        <h2 style="font-weight: normal; font-size: 18px;">Không có đơn đặt sân nào</h2>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>