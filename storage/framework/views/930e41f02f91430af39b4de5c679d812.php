

<?php $__env->startSection('title', 'Quản lý khách đặt sân thể thao'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiển thị thông báo thành công -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <!-- Hiển thị thông báo lỗi -->
    <?php if(session('error')): ?>
        <script>
            alert("<?php echo e(session('error')); ?>");
        </script>
    <?php endif; ?>

    <h3>Danh sách khách đặt sân thể thao</h3>

    <!-- Begin: Date Filter -->
    <div class="admin-time">
        <form method="GET" action="<?php echo e(route('quan-ly-khach-hang')); ?>">
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
                <th>Ảnh thanh toán</th>
                <th>Cập nhật</th>
                <th>Xóa</th>
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
                        <?php if($order->image): ?>
                            <?php
                                $images = json_decode($order->image);
                            ?>
                            <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="Hình ảnh" class="admin-image">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="GET" action="<?php echo e(route('orders.edit', $order->order_id)); ?>">
                            <button type="submit">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('orders.delete', $order->order_id)); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn này?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit">Xóa</button>
                        </form>
                    </td>                                    
                    <td>
                        <form method='POST' action='<?php echo e(route('orders.updateStatus', $order->order_id)); ?>'>
                            <?php echo csrf_field(); ?>
                            <select name='status'>
                                <option value='choxacnhan' <?php echo e($order->status == 'choxacnhan' ? 'selected' : ''); ?>>Chờ xác nhận</option>
                                <option value='xacnhan' <?php echo e($order->status == 'xacnhan' ? 'selected' : ''); ?>>Xác nhận đơn</option>
                                <option value='huydon' <?php echo e($order->status == 'huydon' ? 'selected' : ''); ?>>Hủy đơn</option>
                            </select>
                            <button type='submit'>Cập nhật</button>
                        </form>
                    </td>                                  
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    <?php else: ?>
        <p>Không có kết quả</p>
    <?php endif; ?>
    <!-- End: Display Orders -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>