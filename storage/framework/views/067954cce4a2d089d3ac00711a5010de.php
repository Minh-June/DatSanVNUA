

<?php $__env->startSection('title', 'Lịch sử mua hàng'); ?>

<?php $__env->startSection('content'); ?>
    <h2>Danh sách đơn mua hàng</h2>

    <!-- Begin: Date Filter -->
    <div class="admin-search">
        <form method="GET" action="<?php echo e(route('lich-su-mua-hang')); ?>">
            <label for="date">Ngày:</label>
            <input type="date" id="date" name="date" value="<?php echo e($selectedDate); ?>">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>
    <!-- End: Date Filter -->

    <?php if($groupedOrders->count() > 0): ?>
        <table id="ListCustomers">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Ngày mua</th>
                    <th>Sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Địa chỉ</th>
                    <th>Ghi chú</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
            <?php $index = 1; ?>

            <?php $__currentLoopData = $groupedOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupDate => $orders): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $allDetails = $orders->flatMap(fn($o) => $o->orderDetails);
                    $rowspan = $allDetails->count();
                    $isFirstGroup = true;
                ?>

                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $details = $order->orderDetails;
                        $statusRowspan = $details->count();
                        $isFirstDetail = true;
                    ?>

                    <?php $__currentLoopData = $details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            
                            <?php if($isFirstGroup): ?>
                                <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($index++); ?></td>
                            <?php endif; ?>

                            
                            <?php if($isFirstGroup): ?>
                                <td rowspan="<?php echo e($rowspan); ?>">
                                    <?php echo e(\Carbon\Carbon::parse($groupDate)->format('d/m/Y')); ?><br>
                                    <?php echo e(\Carbon\Carbon::parse($order->date)->format('H:i')); ?>

                                </td>
                            <?php endif; ?>

                            
                            <td class="left-align">
                                <?php
                                    $name = $detail->product->name ?? 'Không xác định';
                                    $size = $detail->size?->name ? 'Size '.$detail->size->name : '';
                                    $chunks = array_chunk(explode(' ', $name), 3);
                                ?>
                                <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e(implode(' ', $chunk)); ?><br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($size): ?>
                                    - <?php echo e($size); ?>

                                <?php endif; ?>
                            </td>

                            
                            <td><?php echo e(number_format($detail->price, 0, ',', '.')); ?>đ</td>

                            
                            <td><?php echo e($detail->quantity); ?></td>

                            
                            <td><?php echo e(number_format($detail->price * $detail->quantity, 0, ',', '.')); ?>đ</td>

                            
                            <?php if($isFirstGroup): ?>
                                <td rowspan="<?php echo e($rowspan); ?>">
                                    <?php
                                        $address = trim($order->address ?? '');
                                        $chunks = array_chunk(explode(' ', $address), 2);
                                    ?>
                                    <?php if(empty($address)): ?>
                                        Không có
                                    <?php else: ?>
                                        <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e(implode(' ', $chunk)); ?><br>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            
                            <?php if($isFirstGroup): ?>
                                <td rowspan="<?php echo e($rowspan); ?>">
                                    <?php
                                        $notes = trim($order->notes ?? '');
                                        $chunks = array_chunk(explode(' ', $notes), 2);
                                    ?>
                                    <?php if(empty($notes)): ?>
                                        Không có
                                    <?php else: ?>
                                        <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e(implode(' ', $chunk)); ?><br>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            
                            <?php if($isFirstGroup): ?>
                                <td rowspan="<?php echo e($rowspan); ?>">
                                    <?php $images = json_decode($order->image) ?? []; ?>
                                    <?php if(count($images)): ?>
                                        <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <img class="order-img" src="<?php echo e(asset('storage/' . $img)); ?>"
                                                onclick="showImage('<?php echo e(asset('storage/' . $img)); ?>')">
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        Thanh toán<br>khi nhận<br>hàng
                                    <?php endif; ?>
                                </td>
                            <?php endif; ?>

                            
                            <?php if($isFirstDetail): ?>
                                <td rowspan="<?php echo e($statusRowspan); ?>">
                                    <?php switch($order->status):
                                        case (0): ?>
                                            <span class="status status-pending">Chờ xác nhận</span>
                                            <?php break; ?>
                                        <?php case (1): ?>
                                            <span class="status status-confirmed">Đã giao hàng</span>
                                            <?php break; ?>
                                        <?php case (2): ?>
                                            <span class="status status-cancelled">Đơn đã bị hủy</span>
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

                        <?php
                            $isFirstDetail = false;
                            $isFirstGroup = false;
                        ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

    <?php else: ?>
        <h2 style="font-weight: normal; font-size: 18px;">Bạn chưa có đơn mua hàng nào</h2>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/account/index-buy.blade.php ENDPATH**/ ?>