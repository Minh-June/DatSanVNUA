

<?php $__env->startSection('title', 'Hợp đồng'); ?>

<?php $__env->startSection('content'); ?>
<div id="content" class="order-section">
    <h2 class="order-heading">Xác nhận thông tin đặt sân</h2>

    <div class="order-successfully">
        <div class="order-successfully-infor">
            <h3 class="order-successfully-header">Hợp đồng đặt sân</h3>

            <h3>Điều 1: Nội dung hợp đồng</h3><br>
            <p>Bên A cam kết và thực hiện đặt lịch sân thể thao theo các thông tin sau đây:</p><br>

            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Ngày đặt</th>
                        <th>Họ và tên</th>
                        <th>SĐT</th>
                        <th>Tên sân</th>
                        <th>Thời gian thuê</th>
                        <th>Ghi chú</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = session('orders', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e(date('d/m/Y', strtotime($order['date']))); ?></td>
                            <?php if($index === 0): ?>
                                <td rowspan="<?php echo e(count(session('orders'))); ?>"><?php echo e($order['name']); ?></td>
                                <td rowspan="<?php echo e(count(session('orders'))); ?>"><?php echo e($order['phone']); ?></td>
                            <?php endif; ?>
                            <td><?php echo e($order['yard_name']); ?></td>
                            <td>
                                <?php $__currentLoopData = $order['times']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e($time); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <!-- <td>
                                <?php $__currentLoopData = $order['times']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e(number_format($order['price_per_slot'][$key] ?? 0)); ?> VNĐ</div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td> -->
                            <td><?php echo e($order['notes'] ?? 'Không có'); ?></td>
                            <td><?php echo e(number_format($order['price'])); ?> VNĐ</td>
                            <td>
                                <form action="<?php echo e(route('xoa-don-tam-thoi')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <input type="hidden" name="index" value="<?php echo e($index); ?>">
                                    <button type="submit" class="update-btn">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>

            </table>

            <h3>Điều 2: Thanh toán</h3>
            <p>Bên A cam kết thanh toán phí dịch vụ đặt lịch theo thỏa thuận giữa hai bên.</p>

            <h3>Điều 3: Điều khoản chung</h3>
            <p>Cả hai bên cam kết thực hiện đúng và đầy đủ các điều khoản trong hợp đồng này.</p>
            <p>Hợp đồng có giá trị từ ngày ký và có thể được điều chỉnh hoặc chấm dứt khi hai bên đồng ý.</p>

            <h3>Điều 4: Ký và xác nhận</h3>
            <p class="order-successfully-day">Hà Nội, ngày <?php echo e(date('d/m/Y')); ?></p>

            <div class="signature">
                <div class="signature-left">
                    <p>Bên A<br><br> <?php echo e(session('orders.0.name')); ?></p>
                </div>
                <div class="signature-right">
                    <p>Bên B<br><br> Group 48</p>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-link">
        <a href="<?php echo e(route('thanh-toan')); ?>" class="order-football-btn">Tiếp tục</a>
    </div>

</div>
<div class="clear"></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/client/success.blade.php ENDPATH**/ ?>