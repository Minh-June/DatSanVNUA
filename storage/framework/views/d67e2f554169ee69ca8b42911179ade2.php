

<?php $__env->startSection('title', 'Quản lý đơn mua hàng thể thao'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>
<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<?php $currentUser = auth()->user(); $today = now()->toDateString(); ?>

<h2>Danh sách đơn mua hàng</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <form method="GET" action="<?php echo e(route('quan-ly-don-mua-hang')); ?>">
            <label for="selected_date">Ngày:</label>
            <input type="date" id="selected_date" name="selected_date" value="<?php echo e($selectedDate); ?>">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>

    <?php if($currentUser->role != 3): ?>
    <div class="admin-add-btn">
        <a class="update-btn" href="<?php echo e(route('trang-chu')); ?>">Thêm đơn mua hàng</a>
    </div>
    <?php endif; ?>
</div>

<?php if($orders->count()): ?>
<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Ngày đặt</th>
            <th>Họ và tên</th>
            <th>SĐT</th>
            <th>Tổng tiền</th>
            <th>Địa chỉ</th>
            <th>Ghi chú</th>
            <th>Thanh toán</th>
            <th>Thông tin</th>
            <th colspan="2">Tùy chọn</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $orderDate = \Carbon\Carbon::parse($order->date)->toDateString();
            $images = $order->image ? json_decode($order->image) : [];
            $statusOptions = [
                0 => 'Chờ xác nhận',
                1 => 'Xác nhận',
                2 => 'Hủy',
                3 => 'Đã đặt cọc'
            ];

            // Quyền chỉnh sửa:
            // Admin (0) và Chủ sân (2) luôn được quyền
            // Nhân viên (3) chỉ thao tác nếu đơn hôm nay trở đi
            $canEdit = ($currentUser->role == 0 || $currentUser->role == 2) ||
                       ($currentUser->role == 3 && $orderDate >= $today);
        ?>

        <tr>
            <td><?php echo e($loop->iteration); ?></td>
            <td>
                <?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?><br>
                <?php echo e(\Carbon\Carbon::parse($order->date)->format('H:i')); ?>

            </td>
            <td class="left-align">
                <?php $__currentLoopData = array_chunk(explode(' ', $order->name), 1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e(implode(' ', $chunk)); ?><br>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </td>
            <td><?php echo e($order->phonenb); ?></td>
            <td><?php echo e(number_format($order->shop_total_price, 0, ',', '.')); ?>đ</td>
            <td>
                <?php
                    $address = trim($order->address ?? '');
                    $addressChunks = $address ? array_chunk(explode(' ', $address), 2) : [];
                ?>
                <?php if($address): ?>
                    <?php $__currentLoopData = $addressChunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(implode(' ', $chunk)); ?><br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    Không có
                <?php endif; ?>
            </td>
            <td>
                <?php
                    $notes = trim($order->notes ?? '');
                    $noteChunks = $notes ? array_chunk(explode(' ', $notes), 2) : [];
                ?>
                <?php if($notes): ?>
                    <?php $__currentLoopData = $noteChunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(implode(' ', $chunk)); ?><br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    Không có
                <?php endif; ?>
            </td>
            <td>
                <?php if($images && count($images) > 0): ?>
                    <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="Ảnh" class="order-img" onclick="showImage(this.src)">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    Thanh toán<br>khi nhận<br>hàng
                <?php endif; ?>
            </td>
            <td>
                <a href="<?php echo e(route('cap-nhat-don-mua-hang', $order->product_order_id)); ?>">Chi tiết</a>
            </td>

            
            <?php if($canEdit): ?>
                <td>
                    <form method="POST" action="<?php echo e(route('cap-nhat-trang-thai-don-mua-hang', $order->product_order_id)); ?>">
                        <?php echo csrf_field(); ?>
                        <select name="status">
                            <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($val != 3 || ($images && count($images))): ?>
                                    <option value="<?php echo e($val); ?>" <?php echo e($order->status == $val ? 'selected' : ''); ?>><?php echo e($text); ?></option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select><br>
                        <button type="submit" class="update-btn">Cập nhật</button>
                    </form>
                </td>

                <?php if($currentUser->role != 3): ?>
                    <td>
                        <form method="POST" action="<?php echo e(route('xoa-don-mua-hang', $order->product_order_id)); ?>" onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                <?php endif; ?>
            <?php else: ?>
                <td colspan="2">
                    <?php switch($order->status):
                        case (0): ?>
                            <span class="status status-pending">Chờ xác nhận</span>
                            <?php break; ?>
                        <?php case (1): ?>
                            <span class="status status-confirmed">Đã xác nhận</span>
                            <?php break; ?>
                        <?php case (2): ?>
                            <span class="status status-cancelled">Đơn đã hủy</span>
                            <?php break; ?>
                        <?php case (3): ?>
                            <span class="status status-deposit">Đã đặt cọc</span>
                            <?php break; ?>
                        <?php default: ?>
                            <span class="status status-unknown">Không xác định</span>
                    <?php endswitch; ?>
                </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php else: ?>
<h2 style="font-weight: normal; font-size: 18px;">Hiện tại chưa có đơn mua hàng nào</h2>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/productorders/index.blade.php ENDPATH**/ ?>