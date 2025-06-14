

<?php $__env->startSection('title', 'Lịch sử đặt sân'); ?>

<?php $__env->startSection('content'); ?> 
    <h2>Danh sách đơn đặt sân</h2>  
    
    <!-- Begin: Date Filter -->
    <div class="admin-search">
        <form method="GET" action="<?php echo e(route('thong-tin-tai-khoan')); ?>">
            <label for="date">Chọn ngày:</label>
            <input type="date" id="date" name="date" value="<?php echo e(request('date', date('Y-m-d'))); ?>">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>        
    <!-- End: Date Filter -->

    <!-- Begin: Display Orders -->
    <?php if($orders->count() > 0): ?>
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>Ngày đặt</th>
                <th>Ngày thuê</th>
                <th>Tên sân</th>
                <th>Thời gian</th>
                <th>Thành tiền</th>
                <th>Ghi chú</th>
                <th>Ảnh thanh toán</th>
                <th>Trạng thái</th>
            </tr>
            <?php $index = 1; ?>
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $groupedDetails = $order->groupedDetails ?? collect();
                    $rowspan = $groupedDetails->count();
                    $isFirstGroup = true;
                ?>

                <?php $__currentLoopData = $groupedDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $firstDetail = $group->first();
                        $timeString = $group->pluck('time')->implode('<br>');
                    ?>

                    <tr>
                        <?php if($isFirstGroup): ?>
                            <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($index++); ?></td>
                            <td rowspan="<?php echo e($rowspan); ?>">
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?><br>
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('H:i')); ?>

                            </td>
                        <?php endif; ?>

                        <td><?php echo e(\Carbon\Carbon::parse($firstDetail->date)->format('d/m/Y')); ?></td>
                        <td><?php echo e($firstDetail->yard->name ?? 'Không xác định'); ?></td>
                        <td><?php echo $timeString; ?></td>

                        <?php if($isFirstGroup): ?>
                            <td rowspan="<?php echo e($rowspan); ?>">
                                <?php echo e(number_format($order->orderDetails->sum('price'), 0, ',', '.')); ?>đ
                            </td>
                            <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($firstDetail->notes ?? 'Không có'); ?></td>
                            <td rowspan="<?php echo e($rowspan); ?>">
                                <?php $images = json_decode($order->image) ?? []; ?>
                                <?php if(count($images)): ?>
                                    <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img src="<?php echo e(asset('storage/' . $img)); ?>" style="width:100px; height:200px;" onclick="showImage('<?php echo e(asset('storage/' . $img)); ?>')">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    Không có ảnh
                                <?php endif; ?>
                            </td>
                            <td rowspan="<?php echo e($rowspan); ?>">
                                <?php switch($order->status):
                                    case (0): ?> Chờ xác nhận <?php break; ?>
                                    <?php case (1): ?> Đã xác nhận <?php break; ?>
                                    <?php case (2): ?> Đơn đã bị hủy <?php break; ?>
                                    <?php default: ?> Không xác định
                                <?php endswitch; ?>
                            </td>
                        <?php endif; ?>
                    </tr>

                    <?php $isFirstGroup = false; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    <?php else: ?>
        <h2 style="font-weight: normal; font-size: 18px;">Hiện chưa có đơn đặt sân nào</h2>
    <?php endif; ?>
    <!-- End: Display Orders -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/account/index.blade.php ENDPATH**/ ?>