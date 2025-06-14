

<?php $__env->startSection('title', 'Hợp đồng'); ?>

<?php $__env->startSection('content'); ?>
<?php if(count(session('orders', [])) === 0): ?>
    <script>
        alert("Vui lòng quay về trang chủ để đặt sân !");
        window.location.href = "<?php echo e(route('trang-chu')); ?>";
    </script>
<?php endif; ?>

<div id="content" class="order-section">
    <h2 class="order-heading">Xác nhận thông tin đặt sân</h2>

    <div class="order-successfully">
        <div class="order-successfully-infor">
            <h3 class="order-successfully-header">Hợp đồng đặt sân</h3>

            <h4>Điều 1: Nội dung hợp đồng</h4>
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
                        <th>Tùy chọn</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $groupedOrders = collect(session('orders', []))->groupBy(fn($order) => $order['date'] . '-' . $order['yard_name']);
                    $stt = 1;
                ?>

                <?php $__currentLoopData = $groupedOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $first = $group->first();
                        $totalPrice = $group->sum('price');
                    ?>
                    <tr>
                        <td rowspan="<?php echo e($group->count()); ?>"><?php echo e($stt++); ?></td>
                        <td rowspan="<?php echo e($group->count()); ?>"><?php echo e(\Carbon\Carbon::parse($first['date'])->format('d/m/Y')); ?></td>
                        <td rowspan="<?php echo e($group->count()); ?>"><?php echo e($first['name']); ?></td>
                        <td rowspan="<?php echo e($group->count()); ?>"><?php echo e($first['phone']); ?></td>
                        <td rowspan="<?php echo e($group->count()); ?>"><?php echo e($first['yard_name']); ?></td>

                        
                        <td>
                            <?php $__currentLoopData = $first['times']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div><?php echo e($time); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td><?php echo e($first['notes'] ?? 'Không có'); ?></td>
                        <td><?php echo e(number_format($first['price'])); ?>đ</td>
                        <td>
                            <form action="<?php echo e(route('xoa-don-tam-thoi')); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn này?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <input type="hidden" name="index" value="<?php echo e(array_search($first, session('orders'))); ?>">
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    </tr>

                    
                    <?php $__currentLoopData = $group->slice(1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <?php $__currentLoopData = $order['times']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e($time); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td><?php echo e($order['notes'] ?? 'Không có'); ?></td>
                            <td><?php echo e(number_format($order['price'])); ?>đ</td>
                            <td>
                                <form action="<?php echo e(route('xoa-don-tam-thoi')); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn này?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <input type="hidden" name="index" value="<?php echo e(array_search($order, session('orders'))); ?>">
                                    <button type="submit" class="delete-btn">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <h4>Điều 2: Thanh toán</h4>
            <p>Bên A cam kết thanh toán phí dịch vụ đặt lịch theo thỏa thuận giữa hai bên.</p>

            <h4>Điều 3: Điều khoản chung</h4>
            <p>Cả hai bên cam kết thực hiện đúng và đầy đủ các điều khoản trong hợp đồng này.</p>
            <p>Hợp đồng có giá trị từ ngày ký và có thể được điều chỉnh hoặc chấm dứt khi hai bên đồng ý.</p>

            <h4>Điều 4: Ký và xác nhận</h4>
            <p class="order-successfully-day">
                Hà Nội, ngày <?php echo e(date('d')); ?> tháng <?php echo e(date('m')); ?> năm <?php echo e(date('Y')); ?>

            </p>
            <div class="signature">
                <div class="signature-left">
                    <p>Bên A</p>
                    <p><?php echo e(session('orders.0.name')); ?></p>
                </div>
                <div class="signature-right">
                    <p>Bên B</p>
                    <p>Group 48</p>
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

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/success.blade.php ENDPATH**/ ?>