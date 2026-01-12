

<?php $__env->startSection('title', 'Lịch sử đặt sân'); ?>

<?php $__env->startSection('content'); ?>
<h2>Danh sách đơn đặt sân</h2>

<!-- Filter theo ngày -->
<div class="admin-top-bar">
    <div class="admin-search">
        <form method="GET" action="<?php echo e(route('thong-tin-tai-khoan')); ?>">
            <label for="date">Ngày:</label>
            <input type="date" id="date" name="date" value="<?php echo e($selectedDate); ?>">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>

    <div class="admin-add-btn"></div>
</div>

<?php if($groupedOrders->count() > 0): ?>
<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Ngày đặt</th>
            <th>Ngày thuê</th>
            <th>Loại sân</th>
            <th>Tên sân</th>
            <th>Khung giờ</th>
            <th>Thành tiền</th>
            <th>Ghi chú</th>
            <th>Thanh toán</th>
            <th>Trạng thái</th>
        </tr>
    </thead>
    <tbody>
    <?php $index = 1; ?>

    <?php $__currentLoopData = $groupedOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orderDate => $ordersInDate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $totalRowsInGroup = $ordersInDate->sum(fn($o) => $o->sortedDetails->count());
            $isFirstOrderInGroup = true;
        ?>

        <?php $__currentLoopData = $ordersInDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                // Nhóm theo: Ngày thuê + Sân + Ghi chú để gộp dòng ghi chú trùng nhau
                $detailsByYardAndNote = $order->sortedDetails->groupBy(fn($d) => 
                    $d->date . '_' . $d->yard_id . '_' . trim($d->notes)
                );
                $orderRowspan = $order->sortedDetails->count();
                $isFirstRowInOrder = true;
            ?>

            <?php $__currentLoopData = $detailsByYardAndNote; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $groupRowspan = $group->count();
                    $isFirstInGroup = true;
                ?>

                <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    
                    <?php if($isFirstOrderInGroup): ?>
                        <td rowspan="<?php echo e($totalRowsInGroup); ?>"><?php echo e($index++); ?></td>
                        <td rowspan="<?php echo e($totalRowsInGroup); ?>">
                            <?php echo e(\Carbon\Carbon::parse($orderDate)->format('d/m/Y')); ?><br>
                            <?php echo e(\Carbon\Carbon::parse($orderDate)->format('H:i')); ?>

                        </td>
                    <?php endif; ?>

                    
                    <?php if($isFirstInGroup): ?>
                        <td rowspan="<?php echo e($groupRowspan); ?>"><?php echo e(\Carbon\Carbon::parse($detail->date)->format('d/m/Y')); ?></td>
                        <td rowspan="<?php echo e($groupRowspan); ?>"><?php echo e($detail->yard->type->name); ?></td>
                        <td rowspan="<?php echo e($groupRowspan); ?>"><?php echo e($detail->yard->name); ?></td>
                    <?php endif; ?>

                    
                    <td><?php echo e($detail->time); ?></td>
                    <td><?php echo e(number_format($detail->price, 0, ',', '.')); ?>đ</td>

                    
                    <?php if($isFirstInGroup): ?>
                        <td rowspan="<?php echo e($groupRowspan); ?>">
                            <?php
                                $words = $detail->notes
                                    ? preg_split('/\s+/', $detail->notes)
                                    : ['Không', 'có'];

                                // mỗi dòng 6–7 từ cho dễ đọc
                                $chunks = array_chunk($words, 4);
                            ?>

                            <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e(implode(' ', $chunk)); ?><br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                    <?php endif; ?>

                    
                    <?php if($isFirstOrderInGroup): ?>
                        <td rowspan="<?php echo e($totalRowsInGroup); ?>" class="payment-cell">
                            <?php
                                $allImages = $ordersInDate->pluck('image')
                                    ->filter(fn($img) => $img && $img !== 'Thanh toán trực tiếp')
                                    ->flatMap(fn($img) => json_decode($img, true) ?? []);
                            ?>
                            <?php if($allImages->count()): ?>
                                <?php $__currentLoopData = $allImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <img src="<?php echo e(asset('storage/' . $img)); ?>" class="order-img" onclick="showImage('<?php echo e(asset('storage/' . $img)); ?>')">
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <span>Thanh toán<br>tại sân</span>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>

                    
                    <?php if($isFirstRowInOrder): ?>
                        <td rowspan="<?php echo e($orderRowspan); ?>">
                            <?php switch($order->status):
                                case (\App\Models\Order::STATUS_PENDING): ?> <span class="status status-pending">Chờ xác nhận</span> <?php break; ?>
                                <?php case (\App\Models\Order::STATUS_CONFIRMED): ?> <span class="status status-confirmed">Đã xác nhận</span> <?php break; ?>
                                <?php case (\App\Models\Order::STATUS_CANCELLED): ?> <span class="status status-cancelled">Đơn đã bị hủy</span> <?php break; ?>
                                <?php case (\App\Models\Order::STATUS_DEPOSIT): ?> <span class="status status-deposit">Đã đặt cọc</span> <?php break; ?>
                            <?php endswitch; ?>
                        </td>
                    <?php endif; ?>
                </tr>
                <?php 
                    $isFirstOrderInGroup = false; 
                    $isFirstRowInOrder = false;
                    $isFirstInGroup = false;
                ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php else: ?>
    <h2 style="font-weight: normal; font-size: 18px;">Bạn chưa có đơn đặt sân nào</h2>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/account/index.blade.php ENDPATH**/ ?>