

<?php $__env->startSection('title', 'Lịch sử đặt sân'); ?>

<?php $__env->startSection('content'); ?> 
    <h3>Danh sách đơn đặt sân</h3>  
    
    <!-- Begin: Date Filter -->
    <div class="admin-search">
        <form method="GET" action="<?php echo e(route('thong-tin-tai-khoan')); ?>">
            <label for="date">Chọn ngày:</label>
            <input type="date" id="date" name="date" value="<?php echo e(request('date', date('Y-m-d'))); ?>">
<<<<<<< HEAD
            <button class="admin-search-btn" type="submit">Tìm kiếm</button>
=======
            <button class="update-btn" type="submit">Tìm kiếm</button>
>>>>>>> 80d6e7c (Cập nhật giao diện)
        </form>
    </div>        
    <!-- End: Date Filter -->

    <!-- Begin: Display Orders -->
    <?php if($orders->count() > 0): ?>
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>Ngày đặt</th>
                <!-- <th>Họ và tên</th>
                <th>Số điện thoại</th> -->
                <th>Ngày thuê</th>
                <th>Tên sân</th>
                <th>Thời gian</th>
                <!-- <th>Giá từng khung giờ</th> -->
                <th>Thành tiền</th>
                <th>Ghi chú</th>
                <th>Ảnh thanh toán</th>
                <th>Trạng thái</th>
            </tr>
            <?php $index = 1; ?>
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $totalDetailsCount = $order->orderDetails->count();
                    $groupedDetails = $order->orderDetails->groupBy(function($item) {
                        return $item->yard_id . '_' . $item->date;
                    });
                    $orderRowspan = $totalDetailsCount;
                ?>

                <?php $__currentLoopData = $groupedDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $firstDetail = $group->first();
                        $timeString = $group->pluck('time')->implode('<br>');
                    ?>

                    <tr>
                        <?php if($loop->parent->first): ?>
                            <td rowspan="<?php echo e($orderRowspan); ?>"><?php echo e($index++); ?></td>
                            <td rowspan="<?php echo e($orderRowspan); ?>">
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?><br>
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('H:i')); ?>

                            </td>
                        <?php endif; ?>

                        <td><?php echo e(\Carbon\Carbon::parse($firstDetail->date)->format('d/m/Y')); ?></td>
                        <td><?php echo e($firstDetail->yard ? $firstDetail->yard->name : 'Không xác định'); ?></td>
                        <td><?php echo $timeString; ?></td>

                        <?php if($loop->parent->first): ?>
                            <td rowspan="<?php echo e($orderRowspan); ?>">
<<<<<<< HEAD
                                <?php echo e(number_format($order->orderDetails->sum('price'), 0, ',', '.')); ?> VND
                            </td>
                            <td rowspan="<?php echo e($orderRowspan); ?>"><?php echo e($firstDetail->notes ?? 'Không có ghi chú'); ?></td>
=======
                                <?php echo e(number_format($order->orderDetails->sum('price'), 0, ',', '.')); ?>đ
                            </td>
                            <td rowspan="<?php echo e($orderRowspan); ?>"><?php echo e($firstDetail->notes ?? 'Không có'); ?></td>
>>>>>>> 80d6e7c (Cập nhật giao diện)
                            <td rowspan="<?php echo e($orderRowspan); ?>">
                                <?php
                                    $images = json_decode($order->image) ?: [];
                                ?>
                                <?php if(count($images) > 0): ?>
                                    <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="Ảnh thanh toán" style="width:100px; height:200px; cursor: pointer;" onclick="showImage('<?php echo e(asset('storage/' . $img)); ?>')">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    Không có ảnh
                                <?php endif; ?>
                            </td>
                            <td rowspan="<?php echo e($orderRowspan); ?>">
                                <?php switch($order->status):
                                    case (0): ?> Chờ xác nhận <?php break; ?>
                                    <?php case (1): ?> Đã xác nhận <?php break; ?>
                                    <?php case (2): ?> Đơn đã bị hủy <?php break; ?>
                                    <?php default: ?> Không xác định
                                <?php endswitch; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    <?php else: ?>
<<<<<<< HEAD
        <h3 style="font-weight: normal; font-size: 17px;">Hiện tại bạn chưa có đơn đặt sân nào.</h3>
=======
        <h3 style="font-weight: normal; font-size: 18px;">Hiện tại bạn chưa có đơn đặt sân nào</h3>
>>>>>>> 80d6e7c (Cập nhật giao diện)
    <?php endif; ?>
    <!-- End: Display Orders -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/account/index.blade.php ENDPATH**/ ?>