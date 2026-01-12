

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
            <label for="selected_date">Ngày:</label>
            <input type="date" id="selected_date" name="selected_date" value="<?php echo e(request('selected_date', now()->toDateString())); ?>">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>

    <div class="admin-add-btn">
        <?php if(auth()->user()->role != 3): ?>
            <a class="update-btn" style="margin-left:10px;" href="<?php echo e(route('trang-chu')); ?>">Thêm đơn đặt sân</a>
        <?php endif; ?>
    </div>
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
                <th>Thanh toán</th>
                <th>Thông tin</th>
                <th colspan="2">Tùy chọn</th>
            </tr>
        </thead>
        <tbody>
        <?php $currentUser = auth()->user(); $today = now()->toDateString(); ?>
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $orderDate = \Carbon\Carbon::parse($order->date)->toDateString();
                $statusOptions = [
                    0 => 'Chờ xác nhận',
                    1 => 'Xác nhận',
                    2 => 'Hủy',
                    3 => 'Đã đặt cọc'
                ];

                $images = json_decode($order->image);

                // Quyền chỉnh sửa: role 0, 2; role 3 nếu là của manager và không phải đơn quá khứ
                $isAdminManaged = $order->orderDetails->contains(fn($d) => $d->yard && $d->yard->user_id == $currentUser->user_id);
                $hasPermission = $order->orderDetails->contains(fn($d) =>
                    ($currentUser->role == 2 && $d->yard->user_id == $currentUser->user_id) ||
                    ($currentUser->role == 3 && $d->yard->user_id == $currentUser->manager_id)
                );

                $canEdit = ($currentUser->role == 0 && $isAdminManaged) ||
                        (in_array($currentUser->role, [2,3]) && $hasPermission && ($currentUser->role != 3 || $orderDate >= $today));
            ?>

            <tr>
                <td><?php echo e($key + 1); ?></td>

                <td>
                    <?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?><br>
                    <?php echo e(\Carbon\Carbon::parse($order->date)->format('H:i')); ?>

                </td>

                <td class="left-align">
                    <?php $__currentLoopData = array_chunk(explode(' ', $order->name), 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e(implode(' ', $chunk)); ?><br>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>

                <td><?php echo e($order->phone); ?></td>

                <td><?php echo e(number_format($order->orderDetails->sum('price'), 0, ',', '.')); ?>đ</td>

                <td>
                    <?php if($images && count($images)): ?>
                        <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="Ảnh" class="order-img" onclick="showImage(this.src)">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        Thanh toán<br>tại sân
                    <?php endif; ?>
                </td>

                <td>
                    <a href="<?php echo e(route('cap-nhat-don-dat-san', $order->order_id)); ?>">Chi tiết</a>
                </td>

                <?php if($canEdit): ?>
                    <td>
                        <form method="POST" action="<?php echo e(route('cap-nhat-trang-thai-don-dat-san', $order->order_id)); ?>">
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
                            <form method="POST" action="<?php echo e(route('xoa-don-dat-san', $order->order_id)); ?>" onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    <?php endif; ?>
                <?php else: ?>
                    <td colspan="2">
                        <?php switch($order->status):
                            case (\App\Models\Order::STATUS_PENDING): ?>
                                <span class="status status-pending">Chờ xác nhận</span>
                                <?php break; ?>
                            <?php case (\App\Models\Order::STATUS_CONFIRMED): ?>
                                <span class="status status-confirmed">Đã xác nhận</span>
                                <?php break; ?>
                            <?php case (\App\Models\Order::STATUS_CANCELLED): ?>
                                <span class="status status-cancelled">Đơn đã hủy</span>
                                <?php break; ?>
                            <?php case (\App\Models\Order::STATUS_DEPOSIT): ?>
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
    <h2 style="font-weight: normal; font-size: 18px;">Hiện tại chưa có đơn đặt sân nào</h2>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>