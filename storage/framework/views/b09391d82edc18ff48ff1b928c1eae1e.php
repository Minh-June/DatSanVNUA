

<?php $__env->startSection('title', 'Lịch sử đặt sân cố định'); ?>

<?php $__env->startSection('content'); ?>
<h2>Danh sách đơn đặt sân cố định</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <form method="GET" action="<?php echo e(route('client.fixed-orders')); ?>">
            <label for="date">Ngày:</label>
            <input type="date" id="date" name="date" value="<?php echo e($selectedDate); ?>">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>
</div>

<?php if($orders->count()): ?>
<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Ngày đặt</th>
            <th>Ngày thuê</th>
            <th>Loại sân</th>
            <th>Thứ</th>
            <th>Tên sân</th>
            <th>Khung giờ</th>
            <th>Thành tiền</th>
            <th>Thanh toán</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
        <?php $stt = 1; ?>
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($stt++); ?></td>
            
            <td>
                <?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?><br>
                <?php echo e(\Carbon\Carbon::parse($order->date)->format('H:i')); ?>

            </td>
            
            <td>
                <?php echo e(\Carbon\Carbon::parse($order->from_date)->format('d/m/Y')); ?><br>-<br>
                <?php echo e(\Carbon\Carbon::parse($order->to_date)->format('d/m/Y')); ?>

            </td>
            <td><?php echo e($order->yard->type->name ?? 'Không xác định'); ?></td>
            <td><?php echo e($order->yard->name ?? 'Không xác định'); ?></td>
            <td>
                <?php $__currentLoopData = explode(',', $order->weekday); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e(['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'][$day] ?? ''); ?><br>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </td>
            <td><?php echo e($order->times); ?></td>
            <td><?php echo e(number_format($order->totalPrice, 0, ',', '.')); ?>đ</td>
            <td>
                <?php if(!empty($order->image)): ?>
                    <?php $__currentLoopData = json_decode($order->image); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img class="order-img" src="<?php echo e(asset('storage/' . $img)); ?>" onclick="showImage('<?php echo e(asset('storage/' . $img)); ?>')">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    Thanh toán<br>tại sân
                <?php endif; ?>
            </td>
            <td>
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
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php else: ?>
<p style="text-align:center; font-size:16px;">Bạn chưa có đơn đặt sân cố định nào</p>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/account/index-fixed.blade.php ENDPATH**/ ?>