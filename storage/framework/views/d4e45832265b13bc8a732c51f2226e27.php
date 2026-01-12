

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
                        <th>Ngày thuê</th>
                        <th>Loại sân</th>
                        <th>Tên sân</th>
                        <th>Thời gian</th>
                        <th>Giá (đ)</th>
                        <th>Ghi chú</th>
                        <th>Tùy chọn</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $orders = collect(session('orders', []));
                    $rows = collect();

                    foreach ($orders as $order) {
                        foreach ($order['times'] as $i => $time) {
                            $rows->push([
                                'name'  => $order['name'],
                                'phone' => $order['phone'],
                                'date'  => $order['date'],
                                'type'  => $order['type_name'],
                                'yard'  => $order['yard_name'],
                                'time'  => $time,
                                'price' => $order['price_per_slot'][$i] ?? 0,
                                'notes' => $order['notes'] ?? 'Không có',
                            ]);
                        }
                    }

                    // Gộp theo Ngày thuê + Tên sân
                    $groups = $rows->groupBy(fn($r) => $r['date'].'_'.$r['yard']);
                    $totalAmount = $rows->sum('price');

                    $globalRowspan = $rows->count();
                    $printedGlobal = false;
                ?>

                <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $rowspan = $group->count();
                        $firstRow = true;
                    ?>

                    <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php if(!$printedGlobal): ?>
                            <td rowspan="<?php echo e($globalRowspan); ?>"><?php echo e($row['name']); ?></td>
                            <td rowspan="<?php echo e($globalRowspan); ?>"><?php echo e($row['phone']); ?></td>
                            <?php $printedGlobal = true; ?>
                        <?php endif; ?>

                        <?php if($firstRow): ?>
                            <td rowspan="<?php echo e($rowspan); ?>">
                                <?php echo e(\Carbon\Carbon::parse($row['date'])->format('d/m/Y')); ?>

                            </td>
                            <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($row['type']); ?></td>
                            <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($row['yard']); ?></td>
                        <?php endif; ?>

                        <td><?php echo e($row['time']); ?></td>
                        <td><?php echo e(number_format($row['price'])); ?>đ</td>
                        <td>
                            <?php
                                $words = preg_split('/\s+/', trim(strip_tags($row['notes'])));
                                $chunks = array_chunk($words, 4);
                            ?>

                            <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e(implode(' ', $chunk)); ?><br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td>
                            <form action="<?php echo e(route('xoa-don-tam-thoi')); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn này ?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <input type="hidden" name="index" value="<?php echo e(array_search($order, session('orders'))); ?>">
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    </tr>

                    <?php $firstRow = false; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($rows->count()): ?>
                <tr>
                    <td colspan="6" style="text-align:right"><b>Tổng tiền</b></td>
                    <td colspan="3"><b><?php echo e(number_format($totalAmount)); ?>đ</b></td>
                </tr>
                <?php endif; ?>
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
                    <?php $__currentLoopData = $owners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($name); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-link" style="margin:40px 0 50px 0;">
        <a href="<?php echo e(route('thanh-toan')); ?>" class="order-football-btn">Tiến hành thanh toán</a>
    </div>

</div>
<div class="clear"></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/success.blade.php ENDPATH**/ ?>