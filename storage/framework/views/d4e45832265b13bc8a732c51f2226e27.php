

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
            <h2 class="order-successfully-header">Hợp đồng đặt sân</h2>

            <h4>Điều 1: Nội dung hợp đồng</h4>
            <p>Bên A cam kết và thực hiện đặt lịch sân thể thao theo các thông tin sau đây:</p><br>

            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>Họ và tên</th>
                        <th>SĐT</th>
                        <th>Ngày đặt</th>
                        <th>Loại sân</th>
                        <th>Tên sân</th>
                        <th>Thời gian thuê</th>
                        <th>Giá từng khung giờ</th>
                        <th>Ghi chú</th>
                        <th>Tùy chọn</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $orders = collect(session('orders', []));
                        $groupedByUser = $orders->groupBy(fn($o) => $o['name'] . '-' . $o['phone']);
                        $totalAmount = $orders->sum('price');
                    ?>

                    <?php $__empty_1 = true; $__currentLoopData = $groupedByUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $rowspanNamePhone = $userGroup->count();
                            $firstNamePhoneRow = true;
                            $groupedByDate = $userGroup->groupBy('date');
                        ?>

                        <?php $__currentLoopData = $groupedByDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $date => $dateGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $rowspanDate = $dateGroup->count();
                                $firstDateRow = true;
                                $groupedByType = $dateGroup->groupBy('type_name');
                            ?>

                            <?php $__currentLoopData = $groupedByType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $typeGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $rowspanType = $typeGroup->count();
                                    $firstTypeRow = true;
                                ?>

                                <?php $__currentLoopData = $typeGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        
                                        <?php if($firstNamePhoneRow): ?>
                                            <td rowspan="<?php echo e($rowspanNamePhone); ?>"><?php echo e($order['name']); ?></td>
                                            <td rowspan="<?php echo e($rowspanNamePhone); ?>"><?php echo e($order['phone']); ?></td>
                                            <?php $firstNamePhoneRow = false; ?>
                                        <?php endif; ?>

                                        
                                        <?php if($firstDateRow): ?>
                                            <td rowspan="<?php echo e($rowspanDate); ?>"><?php echo e(\Carbon\Carbon::parse($date)->format('d/m/Y')); ?></td>
                                            <?php $firstDateRow = false; ?>
                                        <?php endif; ?>

                                        
                                        <?php if($firstTypeRow): ?>
                                            <td class="left-align" rowspan="<?php echo e($rowspanType); ?>"><?php echo e($type); ?></td>
                                            <?php $firstTypeRow = false; ?>
                                        <?php endif; ?>

                                        
                                        <td class="left-align"><?php echo e($order['yard_name']); ?></td>

                                        
                                        <td>
                                            <?php $__currentLoopData = $order['times']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($time); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>

                                        
                                        <td>
                                            <?php $__currentLoopData = $order['price_per_slot'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(number_format($price)); ?>đ<br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>

                                        
                                        <td><?php echo e($order['notes'] ?? 'Không có'); ?></td>

                                        
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
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="9">Không có đơn đặt sân nào !</td></tr>
                    <?php endif; ?>
                </tbody>

                <?php if(count($orders) > 0): ?>
                <tfoot>
                    <tr>
                        <td colspan="6" style="text-align: right;"><strong>Tổng tiền:</strong></td>
                        <td colspan="3"><strong><?php echo e(number_format($totalAmount)); ?>đ</strong></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
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