

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

    
    <div class="pay-method" style="margin-bottom: 20px;">
        <p style="color:red;">Đơn của bạn còn được giữ chỗ trong</p>
        <p id="countdown" style="color:red;">10:00</p>
    </div>

    
    <div class="pay-method" style="margin-bottom: 20px;">
        <p>Hình thức thanh toán</p>
        <label style="margin-right: 20px;">
            <input type="radio" name="payment_method" value="offline" checked>
            Thanh toán trực tiếp tại sân
        </label>
        <label>
            <input type="radio" name="payment_method" value="online">
            Thanh toán trước khi đến sân
        </label>
    </div>

    <?php
        // Tạo orderKey riêng cho đơn hiện tại
        $orderKey = session('current_order_key') ?? uniqid('order_');
        session(['current_order_key' => $orderKey]);
    ?>

    
    <div class="pay-customer offline-group" style="margin-bottom:300px">
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
                $ordersCollection = collect($orders);
                $groupedByOwner = $ordersCollection->groupBy('yard_owner_id');
            ?>

            <?php $__currentLoopData = $groupedByOwner; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ownerOrders): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $rows = collect();

                    foreach ($ownerOrders as $order) {
                        foreach ($order['times'] as $i => $time) {
                            $rows->push([
                                'name' => $order['name'],
                                'phone' => $order['phone'],
                                'date' => $order['date'],
                                'type_name' => $order['type_name'],
                                'yard_name' => $order['yard_name'],
                                'time' => $time,
                                'price' => $order['price_per_slot'][$i] ?? 0,
                                'notes' => $order['notes'] ?? 'Không có',
                            ]);
                        }
                    }

                    // sort theo ngày + giờ
                    $rows = $rows->sortBy(function ($r) {
                        return \Carbon\Carbon::createFromFormat(
                            'Y-m-d H:i',
                            $r['date'].' '.substr($r['time'], 0, 5)
                        )->timestamp;
                    })->values();

                    // gộp theo Ngày + Tên sân
                    $groupByDateYard = $rows->groupBy(fn($r) => $r['date'].'_'.$r['yard_name']);

                    $totalAmount = $rows->sum('price');
                    $globalRowspan = $rows->count();
                    $firstGlobal = true;
                ?>

                <?php $__currentLoopData = $groupByDateYard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $rowspan = $group->count();
                        $firstRow = true;
                    ?>

                    <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        
                        <?php if($firstGlobal): ?>
                            <td rowspan="<?php echo e($globalRowspan); ?>"><?php echo e($row['name']); ?></td>
                            <td rowspan="<?php echo e($globalRowspan); ?>"><?php echo e($row['phone']); ?></td>
                        <?php endif; ?>

                        
                        <?php if($firstRow): ?>
                            <td rowspan="<?php echo e($rowspan); ?>">
                                <?php echo e(\Carbon\Carbon::parse($row['date'])->format('d/m/Y')); ?>

                            </td>
                            <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($row['type_name']); ?></td>
                            <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($row['yard_name']); ?></td>
                        <?php endif; ?>

                        <td><?php echo e($row['time']); ?></td>
                        <td><?php echo e(number_format($row['price'])); ?>đ</td>
                        <td><?php echo e($row['notes']); ?></td>
                    </tr>

                    <?php
                        $firstRow = false;
                        $firstGlobal = false;
                    ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <tr>
                    <td colspan="6" style="text-align:right;font-weight:bold;">Tổng tiền:</td>
                    <td colspan="2" style="font-weight:bold;">
                        <?php echo e(number_format($totalAmount)); ?>đ
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($groupedByOwner->isEmpty()): ?>
            <tr>
                <td colspan="8">Không có đơn đặt sân nào!</td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>

        
        <form id="offlineForm" action="<?php echo e(route('pay.offline')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="notes" value="<?php echo e($orders[0]['notes'] ?? 'Không có'); ?>">
            <input type="hidden" name="order_key" value="<?php echo e($orderKey); ?>">
            <div class="pay-btn" style="margin-top: 20px; text-align: center;">
                <button type="submit" class="order-football-btn">Xác nhận đặt sân</button>
            </div>
        </form>
    </div>

    
    <div class="pay-customer online-group" style="display:none; margin-bottom:300px">
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
                <th>Tùy chọn</th>
            </tr>
            </thead>

            <tbody>
            <?php
                $ordersCollection = collect($orders);
                $groupedByOwner = $ordersCollection->groupBy('yard_owner_id');
            ?>

            <?php $__currentLoopData = $groupedByOwner; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ownerId => $ownerOrders): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $rows = collect();

                foreach ($ownerOrders as $order) {
                    foreach ($order['times'] as $i => $time) {
                        $rows->push([
                            'name' => $order['name'],
                            'phone' => $order['phone'],
                            'date' => $order['date'],
                            'type_name' => $order['type_name'],
                            'yard_name' => $order['yard_name'],
                            'time' => $time,
                            'price' => $order['price_per_slot'][$i] ?? 0,
                            'notes' => $order['notes'] ?? 'Không có',
                        ]);
                    }
                }

                // sort ngày → giờ
                $rows = $rows->sortBy(fn($r) =>
                    \Carbon\Carbon::createFromFormat(
                        'Y-m-d H:i',
                        $r['date'].' '.substr($r['time'], 0, 5)
                    )->timestamp
                )->values();

                // gộp theo ngày + sân
                $groupByDateYard = $rows->groupBy(fn($r) => $r['date'].'_'.$r['yard_name']);

                $totalAmount = $rows->sum('price');
                $globalRowspan = $rows->count();
                $firstGlobal = true;
            ?>

            <?php $__currentLoopData = $groupByDateYard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $rowspan = $group->count();
                    $firstRow = true;
                ?>

                <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    
                    <?php if($firstGlobal): ?>
                        <td rowspan="<?php echo e($globalRowspan); ?>"><?php echo e($row['name']); ?></td>
                        <td rowspan="<?php echo e($globalRowspan); ?>"><?php echo e($row['phone']); ?></td>
                    <?php endif; ?>

                    
                    <?php if($firstRow): ?>
                        <td rowspan="<?php echo e($rowspan); ?>">
                            <?php echo e(\Carbon\Carbon::parse($row['date'])->format('d/m/Y')); ?>

                        </td>
                        <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($row['type_name']); ?></td>
                        <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($row['yard_name']); ?></td>
                    <?php endif; ?>

                    <td><?php echo e($row['time']); ?></td>
                    <td><?php echo e(number_format($row['price'])); ?>đ</td>
                    <td><?php echo e($row['notes']); ?></td>

                    <?php if($firstGlobal): ?>
                        <td rowspan="<?php echo e($globalRowspan); ?>">
                            <button class="order-football-btn btn-pay"
                                    style="font-size:17px;"
                                    data-owner="<?php echo e($ownerId); ?>">
                                Thanh toán
                            </button>
                        </td>
                    <?php endif; ?>
                </tr>

                <?php
                    $firstRow = false;
                    $firstGlobal = false;
                ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            
            <tr>
                <td colspan="6" style="text-align:right;font-weight:bold;">Tổng tiền:</td>
                <td colspan="3" style="font-weight:bold;">
                    <?php echo e(number_format($totalAmount)); ?>đ
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($groupedByOwner->isEmpty()): ?>
            <tr>
                <td colspan="9">Không có đơn đặt sân nào!</td>
            </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const offlineGroup = document.querySelector('.offline-group');
    const onlineGroup = document.querySelector('.online-group');
    const countdownEl = document.getElementById('countdown');
    const offlineForm = document.querySelector('#offlineForm');

    // orderKey riêng cho đơn hiện tại
    const orderKey = "<?php echo e($orderKey); ?>";

    // --- Cấu hình thời gian đếm ngược (phút) ---
    const COUNTDOWN_MINUTES = 10;

    // --- Khởi tạo layout theo radio ---
    function updateLayout(value) {
        if (value === 'online') {
            offlineGroup && (offlineGroup.style.display = 'none');
            onlineGroup && (onlineGroup.style.display = 'block');
        } else {
            offlineGroup && (offlineGroup.style.display = 'block');
            onlineGroup && (onlineGroup.style.display = 'none');
        }
    }
    const selectedMethod = localStorage.getItem('payment_method_' + orderKey) || document.querySelector('input[name="payment_method"]:checked').value;
    updateLayout(selectedMethod);
    radios.forEach(r => r.checked = (r.value === selectedMethod));
    radios.forEach(r => {
        r.addEventListener('change', function () {
            updateLayout(this.value);
            localStorage.setItem('payment_method_' + orderKey, this.value);
        });
    });

    // --- Chuyển sang trang thanh toán ONLINE ---
    document.querySelectorAll('.btn-pay').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const ownerId = this.dataset.owner;
            if(ownerId) window.location.href = `/thanh-toan/online/${ownerId}`;
            else alert('Không tìm thấy mã chủ sân!');
        });
    });

    // --- Countdown giữ nguyên khi chuyển sang ONLINE ---
    let remainingTime = localStorage.getItem('payment_remaining_' + orderKey);
    if (!remainingTime) {
        remainingTime = COUNTDOWN_MINUTES * 60;
    }
    remainingTime = parseInt(remainingTime);

    const timer = setInterval(() => {
        const min = Math.floor(remainingTime / 60);
        const sec = remainingTime % 60;
        countdownEl.textContent = `${min.toString().padStart(2,'0')}:${sec.toString().padStart(2,'0')}`;

        if (remainingTime <= 0) {
            clearInterval(timer);
            localStorage.removeItem('payment_remaining_' + orderKey);

            // Chỉ hiện alert với 1 nút OK
            alert("Vui lòng quay về trang chủ đặt sân !");
            window.location.href = "<?php echo e(route('payment.timeout')); ?>";
            return;
        }

        remainingTime--;
        localStorage.setItem('payment_remaining_' + orderKey, remainingTime);
    }, 1000);

    // Dừng countdown khi submit form Offline
    if (offlineForm) {
        offlineForm.addEventListener('submit', function () {
            clearInterval(timer);
            localStorage.removeItem('payment_remaining_' + orderKey);
            
            // Xóa orderKey cũ trong session để lần sau tạo mới
            fetch("<?php echo e(route('payment.timeout')); ?>", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ reset_order_key: true })
            });
        });
    }

});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/pay.blade.php ENDPATH**/ ?>