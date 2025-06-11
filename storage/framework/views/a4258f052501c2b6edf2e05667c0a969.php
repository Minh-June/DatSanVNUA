

<?php $__env->startSection('title', 'Cáº­p nháº­t thĂ´ng tin Ä‘Æ¡n Ä‘áº·t sĂ¢n'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('price_change_message')): ?>
        <script>
            alert("<?php echo e(session('price_change_message')); ?>");
        </script>
    <?php endif; ?>

    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o lá»—i -->
    <?php if(session('error')): ?>
        <script>
            alert("<?php echo e(session('error')); ?>");
        </script>
    <?php endif; ?>
    
    <h3>Chi tiáº¿t Ä‘Æ¡n Ä‘áº·t sĂ¢n</h3>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            <a href="<?php echo e(route('quan-ly-don-dat-san')); ?>">Quay láº¡i danh sĂ¡ch</a>
        </div>
    </div>

    <table id="ListCustomers">
        <thead>
            <tr>
                <th>STT</th>
                <th>TĂªn sĂ¢n</th>
                <th>NgĂ y thuĂª</th>
                <th>Khung giá»</th>
                <th>GiĂ¡</th>
                <th>Ghi chĂº</th>
                <th colspan="2">TĂ¹y chá»n</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e($detail->yard->name ?? 'KhĂ´ng xĂ¡c Ä‘á»‹nh'); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($detail->date)->format('d/m/Y')); ?></td>
                    <td><?php echo e(optional($detail->time)->time ?? $detail->time); ?></td>
                    <td><?php echo e(number_format($detail->price, 0, ',', '.')); ?> VND</td>
                    <td><?php echo e($detail->notes ?: 'KhĂ´ng cĂ³'); ?></td>
                    <td>
                        <form action="<?php echo e(route('cap-nhat-chi-tiet-don', $detail->order_detail_id)); ?>" method="GET" style="display:inline;">
                            <button type="submit" class="update-btn">Sá»­a</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('xoa-chi-tiet-don', $detail->order_detail_id)); ?>" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c muá»‘n xĂ³a chi tiáº¿t nĂ y?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="update-btn">XĂ³a</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right;"><strong>Tá»•ng tiá»n:</strong></td>
                <td colspan="2"><strong><?php echo e(number_format($totalPrice, 0, ',', '.')); ?> VND</strong></td>
            </tr>
        </tfoot>
    </table>

    <?php if(isset($editDetail) && $editDetail): ?>
        <div class="adminedit">
            
            <form method="GET" action="<?php echo e(route('cap-nhat-chi-tiet-don', $editDetail->order_detail_id)); ?>" id="form-select-yard-date">
                <label>Chá»n sĂ¢n:</label>
                <select name="yard_id" id="yard_id" required onchange="document.getElementById('form-select-yard-date').submit()">
                    <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $san): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($san->yard_id); ?>"
                            <?php echo e(request('yard_id', $editDetail->yard_id) == $san->yard_id ? 'selected' : ''); ?>>
                            <?php echo e($san->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select><br>

                <label>NgĂ y thuĂª:</label>
                <input type="date" name="date" id="date"
                    value="<?php echo e(request('date', $editDetail->date)); ?>"
                    required onchange="document.getElementById('form-select-yard-date').submit()">
            </form>

            
            <form method="POST" action="<?php echo e(route('update.order_detail', $editDetail->order_detail_id)); ?>">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="yard_id" value="<?php echo e(request('yard_id', $editDetail->yard_id)); ?>">
                <input type="hidden" name="date" value="<?php echo e(request('date', $editDetail->date)); ?>">

                <label>Khung giá»:</label>
                <select name="time" id="time" required onchange="updatePrice()">
                    <?php $__currentLoopData = $timesForSelectedDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($time->time); ?>" data-price="<?php echo e($time->price); ?>"
                            <?php echo e(old('time', $editDetail->time ?? '') == $time->time ? 'selected' : ''); ?>>
                            <?php echo e($time->time); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select><br>

                <label>GiĂ¡:</label>
                <input type="text" id="price_display" value="" disabled>
                <input type="hidden" name="price" id="price" value=""><br>

                <label>Ghi chĂº:</label><br>
                <textarea name="notes" rows="3"><?php echo e(old('notes', $editDetail->notes ?? '')); ?></textarea><br>

                <button class="update-btn" type="submit">Cáº­p nháº­t</button><br><br>
            </form>
        </div>
    <?php endif; ?>

    <script>
        // HĂ m cáº­p nháº­t giĂ¡ khi chá»n khung giá»
        function updatePrice() {
            const timeSelect = document.getElementById('time');
            const selectedOption = timeSelect.options[timeSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            document.getElementById('price_display').value = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
            document.getElementById('price').value = price;
        }

        // Khi load trang, hiá»ƒn thá»‹ giĂ¡ khung giá» Ä‘áº§u tiĂªn
        window.onload = function () {
            updatePrice();
        };
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/orders/update.blade.php ENDPATH**/ ?>
