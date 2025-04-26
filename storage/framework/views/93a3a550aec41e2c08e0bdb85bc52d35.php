

<?php $__env->startSection('title', 'Trung tâm tài khoản'); ?>

<?php $__env->startSection('content'); ?> 
                        <h3>Danh sách sân đã đặt</h3>  
                        
                        <!-- Begin: Date Filter -->
                        <div class="admin-time">
                            <form method="GET" action="<?php echo e(route('thong-tin-tai-khoan')); ?>">
                                <label for="selected_date">Chọn ngày:</label>
                                <input type="date" id="selected_date" name="selected_date" value="<?php echo e(request('selected_date')); ?>" required>
                                <button class="admin-time-btn" type="submit" name="filter_date">Tìm kiếm</button>
                            </form>
                        </div>        
                        <!-- End: Date Filter -->

                        <!-- Begin: Display Orders -->
                        <?php if($orders->count() > 0): ?>
                            <table id="ListCustomers">
                                <tr>
                                    <th>STT</th>
                                    <th>Họ và tên</th>
                                    <th>Số điện thoại</th>
                                    <th>Tên sân</th>
                                    <th>Số sân</th>
                                    <th>Ngày</th>
                                    <th>Thời gian</th>
                                    <th>Thành tiền</th>
                                    <th>Ghi chú</th>
                                    <th>Trạng thái</th>
                                </tr>
                                <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($key + 1); ?></td>
                                        <td><?php echo e($order->name); ?></td>
                                        <td><?php echo e($order->phone); ?></td>
                                        <td><?php echo e($order->san ? $order->san->tensan : 'Không xác định'); ?></td>
                                        <td><?php echo e($order->san ? $order->san->sosan : 'Không xác định'); ?></td>
                                        <td><?php echo e($order->date); ?></td>
                                        <td>
                                            <?php $__currentLoopData = explode(',', $order->time); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeSlot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($timeSlot); ?> <br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td><?php echo e($order->price); ?> VND</td>
                                        <td><?php echo e($order->notes); ?></td>
                                        <td>
                                            <?php switch($order->status):
                                                case ('choxacnhan'): ?>
                                                    Chờ xác nhận
                                                    <?php break; ?>
                                                <?php case ('xacnhan'): ?>
                                                    Đã xác nhận
                                                    <?php break; ?>
                                                <?php case ('huydon'): ?>
                                                    Đơn đã bị hủy
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    Không xác định
                                            <?php endswitch; ?>
                                        </td>                                        
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        <?php else: ?>
                            <p>Không có kết quả</p>
                        <?php endif; ?>
                        <!-- End: Display Orders -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.client.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/user/index.blade.php ENDPATH**/ ?>