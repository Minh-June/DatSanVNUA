

<?php $__env->startSection('title', 'Thanh toán ngay'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>  

<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<div id="content" class="order-section">
    <h2 class="order-heading">THANH TOÁN</h2>
    
    
    <div class="pay-method" style="margin-bottom: 20px;">
        <p style="color:red;">Đơn của bạn còn được giữ chỗ trong</p>
        <p id="countdown" style="color:red;">10:00</p>
    </div>

    
    <div class="pay-method" style="margin-bottom: 20px;">
        <p>Hình thức thanh toán</p>
        <label style="display: inline-flex; align-items: center; margin-right: 20px; cursor: pointer;">
            <input type="radio" name="payment_method" value="offline" checked style="margin-right: 5px;">
            <a href="<?php echo e(url('/thanh-toan')); ?>" style="color: #333; text-decoration: none; font-size:20px; margin-top:-5px;">Thanh toán trực tiếp tại sân</a>
        </label>

        <label>
            <input type="radio" name="payment_method" value="online" checked>
            Thanh toán trước khi đến sân
        </label>
    </div>

    <?php
        // Tạo orderKey riêng cho đơn hiện tại
        $orderKey = session('current_order_key') ?? uniqid('order_');
        session(['current_order_key' => $orderKey]);
    ?>

    
    <div class="pay-customer online-group">
        <?php
            $ownerModel = \App\Models\User::find($ownerOrders[0]['yard_owner_id'] ?? 0);
        ?>
        <p>Thông tin thanh toán</p>
        <div class="pay-content" style="flex-wrap: wrap; margin-bottom: 20px;">
            <div class="pay-information">
                <div class="bank-account">Thông tin ngân hàng</div>
                <?php if($ownerModel): ?>
                    <div class="bank-account">Ngân hàng: <?php echo e($ownerModel->acc_type ?? ''); ?></div>
                    <div class="bank-account">Số tài khoản: <?php echo e($ownerModel->acc_number ?? ''); ?></div>
                    <div class="bank-account">Tên tài khoản: <?php echo e($ownerModel->acc_name ?? ''); ?></div>
                <?php endif; ?>
            </div>
            <div class="pay-information">
                <?php if(!empty($ownerModel?->qr_code)): ?>
                    <div class="bank-qr">
                        <img class="bank-qr-img" src="<?php echo e(asset('storage/' . $ownerModel->qr_code)); ?>" alt="Mã QR"><br>
                        Mã QR
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <p>Thông tin đơn đặt sân</p><br>
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
                </tr>
            </thead>
            <tbody>
                <?php
                    $ordersCollection = collect($ownerOrders);
                    $groupedByUser = $ordersCollection->groupBy(fn($o) => $o['name'].'-'.$o['phone']);
                    $totalAmount = 0;
                ?>

                <?php $__currentLoopData = $groupedByUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                $groupedByYard = $typeGroup->groupBy('yard_name');
                            ?>

                            <?php $__currentLoopData = $groupedByYard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yard => $yardGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $rowspanYard = $yardGroup->count();
                                    $firstYardRow = true;
                                ?>

                                <?php $__currentLoopData = $yardGroup; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                            <td rowspan="<?php echo e($rowspanType); ?>"><?php echo e($type); ?></td>
                                            <?php $firstTypeRow = false; ?>
                                        <?php endif; ?>

                                        
                                        <?php if($firstYardRow): ?>
                                            <td rowspan="<?php echo e($rowspanYard); ?>"><?php echo e($yard); ?></td>
                                            <?php $firstYardRow = false; ?>
                                        <?php endif; ?>

                                        
                                        <td>
                                            <?php $__currentLoopData = $order['times']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e($time); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>

                                        
                                        <td>
                                            <?php $__currentLoopData = $order['price_per_slot'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $price): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(number_format($price)); ?>đ<br>
                                                <?php $totalAmount += $price; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>

                                        
                                        <td>
                                            <?php
                                                // Tách chuỗi thành mảng từ
                                                $words = isset($order['notes']) ? explode(' ', $order['notes']) : ['Không', 'có'];
                                                // Chia mảng thành từng chunk 5 từ
                                                $chunks = array_chunk($words, 7);
                                            ?>

                                            <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php echo e(implode(' ', $chunk)); ?><br>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php if($totalAmount > 0): ?>
                    <tr>
                        <td colspan="6" style="text-align:right;font-weight:bold;">Tổng tiền:</td>
                        <td colspan="2" style="font-weight:bold;"><?php echo e(number_format($totalAmount)); ?>đ</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <form action="<?php echo e(route('pay.online')); ?>" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            
            <input type="hidden" name="notes" 
                value="<?php echo e(isset($ownerOrders[0]['notes']) ? $ownerOrders[0]['notes'] : 'Không có'); ?>">

            <input type="hidden" name="owner_id" value="<?php echo e($ownerModel->user_id ?? 0); ?>">

            <div class="pay-upload">
                <p>* LƯU Ý:<br>
                    Nội dung chuyển khoản: TÊN + SĐT<br>
                    Chuyển khoản ĐÚNG số tiền ở phần "Tổng tiền"<br>
                    Sau khi hoàn tất, chụp lại màn hình giao dịch và gửi ảnh bên dưới
                </p><br>
                <input type="file" name="images[]" multiple accept=".jpg,.jpeg,.png"><br><br>
            </div>

            <div class="pay-btn" style="margin-bottom: 100px; text-align: center;">
                <button type="submit" class="order-football-btn">Xác nhận đặt sân</button>
            </div>
        </form>
        
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form[action="<?php echo e(route('pay.online')); ?>"]');
            form.addEventListener('submit', function(e) {
                const files = form.querySelector('input[name="images[]"]').files;
                if (files.length === 0) {
                    e.preventDefault();
                    alert("Vui lòng tải ảnh thanh toán thành công hoặc chọn phương thức thanh toán khác.");
                }
            });
        });
        </script>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const countdownEl = document.getElementById('countdown');

    // orderKey giống với OFFline để countdown tiếp nối
    const orderKey = "<?php echo e($orderKey); ?>";

    // Lấy thời gian còn lại từ localStorage
    let remainingTime = localStorage.getItem('payment_remaining_' + orderKey);
    if (!remainingTime) {
        remainingTime = 10 * 60; // <-- chỉnh ở đây nếu muốn thay đổi mặc định
    }
    remainingTime = parseInt(remainingTime);

    const timer = setInterval(() => {
        if (remainingTime <= 0) {
            clearInterval(timer);
            localStorage.removeItem('payment_remaining_' + orderKey);

            // Hiện alert, người dùng chỉ có nút OK
            alert("Vui lòng quay về trang chủ đặt sân !");
            window.location.href = "<?php echo e(route('payment.timeout')); ?>";
            return;
        }

        const min = Math.floor(remainingTime / 60);
        const sec = remainingTime % 60;
        countdownEl.textContent = `${min.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')}`;

        remainingTime--;
        localStorage.setItem('payment_remaining_' + orderKey, remainingTime);
    }, 1000);

    // --- Hiển thị form theo radio ---
    const offlineGroup = document.querySelector('.offline-group');
    const onlineGroup = document.querySelector('.online-group');
    const offlineRadio = document.querySelector('input[name="payment_method"][value="offline"]');
    const onlineRadio = document.querySelector('input[name="payment_method"][value="online"]');

    function updateLayout(value) {
        if (value === 'online') {
            offlineGroup && (offlineGroup.style.display = 'none');
            onlineGroup && (onlineGroup.style.display = 'block');
        } else {
            offlineGroup && (offlineGroup.style.display = 'block');
            onlineGroup && (onlineGroup.style.display = 'none');
        }
    }

    // Khởi tạo layout
    const selected = localStorage.getItem('payment_method_' + orderKey) || 
                     document.querySelector('input[name="payment_method"]:checked')?.value || 'offline';
    updateLayout(selected);
    [offlineRadio, onlineRadio].forEach(r => r.checked = (r.value === selected));

    // Lắng nghe thay đổi radio
    [offlineRadio, onlineRadio].forEach(radio => {
        radio.addEventListener('change', function () {
            updateLayout(this.value);
            localStorage.setItem('payment_method_' + orderKey, this.value);
        });
    });

    // Dừng countdown khi submit form Online
    const onlineForm = document.querySelector('.online-group form');
    if (onlineForm) {
        onlineForm.addEventListener('submit', function () {
            clearInterval(timer);
            localStorage.removeItem('payment_remaining_' + orderKey); // reset countdown cho lần đặt tiếp theo
        });
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/paynow.blade.php ENDPATH**/ ?>