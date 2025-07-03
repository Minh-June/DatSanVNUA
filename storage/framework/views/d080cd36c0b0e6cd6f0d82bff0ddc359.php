

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
                <img class="bank-qr-img" src="<?php echo e(asset('image/qr/qr.jpg')); ?>" alt="Mã QR"> <br>
                Mã QR
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <div class="pay-customer">
        <h2>Thông tin đơn đặt sân</h2><br>

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
                </tr>
            </thead>
            <tbody>
                <?php
                    $totalAmount = 0;
                    $groupedByUser = collect($orders)->groupBy(fn($o) => $o['name'] . '-' . $o['phone']);
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

                            <?php $__currentLoopData = $typeGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                </tr>
                                <?php $totalAmount += $order['price'] ?? 0; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8">Không có đơn đặt sân nào.</td></tr>
                <?php endif; ?>
            </tbody>

            <?php if(count($orders) > 0): ?>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align: right;"><strong>Tổng tiền:</strong></td>
                    <td colspan="2"><strong><?php echo e(number_format($totalAmount)); ?>đ</strong></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>

        <?php if(count($orders) > 0): ?>
        <div class="pay-upload">
            <p>* LƯU Ý: Nếu bạn muốn thanh toán trước<br><br>
                Chuyển khoản ĐÚNG số tiền ở phần "Tổng tiền"<br><br>
                Nội dung chuyển khoản: TÊN + SĐT<br><br>
                Sau khi hoàn tất, chụp lại màn hình giao dịch và gửi ảnh bên dưới</p>

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

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/pay.blade.php ENDPATH**/ ?>