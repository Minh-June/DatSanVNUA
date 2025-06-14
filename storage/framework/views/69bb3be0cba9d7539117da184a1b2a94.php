

<?php $__env->startSection('title', 'Thanh toán'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

<div id="content" class="order-section">
    <h2 class="order-heading">THANH TOÁN</h2>

    <div class="pay-content">
        <div class="pay-information">
            <div class="bank-account">Tài khoản ngân hàng</div>
            <div class="bank-account">Tên tài khoản: Nguyễn Hữu Quang Minh</div>
            <div class="bank-account">Số tài khoản: 1903 6786 8800 12</div>
            <div class="bank-account">Ngân hàng: Techcombank</div>
        </div>
        <div class="pay-information">
            <div class="bank-qr">
                Mã QR <br>
                <img class="bank-qr-img" src="<?php echo e(asset('image/qr/qr.jpg')); ?>" alt="Mã QR">
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <div class="pay-customer">
        <h3>Thông tin đơn đặt sân</h3><br>

        <table id="ListCustomers">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Ngày đặt</th>
                    <th>Họ và tên</th>
                    <th>SĐT</th>
                    <th>Tên sân</th>
                    <th>Thời gian thuê</th>
                    <th>Giá từng khung giờ</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalAmount = 0; ?>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $totalAmount += $order['price']; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($order['date'])->format('d/m/Y')); ?></td>
                        <?php if($index === 0): ?>
                            <td rowspan="<?php echo e(count($orders)); ?>"><?php echo e($order['name']); ?></td>
                            <td rowspan="<?php echo e(count($orders)); ?>"><?php echo e($order['phone']); ?></td>
                        <?php endif; ?>
                        <td><?php echo e($order['yard_name']); ?></td>
                        <td>
                            <?php $__currentLoopData = $order['times']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($time); ?><br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td>
                            <?php if(!empty($order['price_per_slot']) && is_array($order['price_per_slot'])): ?>
                            <?php $__currentLoopData = $order['price_per_slot']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(number_format($price)); ?> VNĐ<br>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                Không có dữ liệu
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($order['notes'] ?? 'Không có ghi chú'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8">Không có đơn đặt sân nào.</td></tr>
                <?php endif; ?>
            </tbody>
            <?php if(count($orders) > 0): ?>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align: right;"><strong>Tổng tiền:</strong></td>
                    <td colspan="2"><strong><?php echo e(number_format($totalAmount)); ?> VNĐ</strong></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>

        <?php if(count($orders) > 0): ?>
        <div class="pay-upload">
            <p>* LƯU Ý: Nếu bạn muốn thanh toán trước<br><br>
                Chuyển khoản ĐÚNG số tiền ở phần "Tổng tiền"<br><br>
                Nội dung chuyển khoản: TÊN + SĐT<br><br>
                Sau khi hoàn tất, chụp lại màn hình giao dịch và gửi ảnh bên dưới.</p>

            <form action="<?php echo e(route('pay.upload')); ?>" method="post" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png"><br><br>
                <div class="pay-btn">
                    <button type="submit" class="order-football-btn">Xác nhận đặt sân</button>
                </div>
            </form>
        </div>
        <?php endif; ?>
    </div>

    <div class="clear"></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/client/pay.blade.php ENDPATH**/ ?>