

<?php $__env->startSection('title', 'Cập nhật chi tiết đơn đặt sân'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('price_change_message')): ?>
        <script>
            alert("<?php echo e(session('price_change_message')); ?>");
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            alert("<?php echo e(session('error')); ?>");
        </script>
    <?php endif; ?>
    
    <h2>Chi tiết đơn đặt sân</h2>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            <a class="update-btn" href="<?php echo e(route('quan-ly-don-dat-san')); ?>">Quay lại danh sách</a>
        </div>
    </div>

    <table id="ListCustomers">
        <thead>
            <tr>
                <th>STT</th>
                <th>Ngày thuê</th>
                <th>Loại sân</th>
                <th>Tên sân</th>
                <th>Khung giờ</th>
                <th>Giá</th>
                <th>Ghi chú</th>
                <th colspan="2">Tùy chọn</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?></td>
                    <td><?php echo e(\Carbon\Carbon::parse($detail->date)->format('d/m/Y')); ?></td>
                    <td class="left-align"><?php echo e($detail->yard->type->name ?? 'Loại sân không tồn tại'); ?></td>
                    <td class="left-align"><?php echo e($detail->yard->name ?? 'Sân không tồn tại'); ?></td>
                    <td><?php echo e(optional($detail->time)->time ?? $detail->time); ?></td>
                    <td><?php echo e(number_format($detail->price, 0, ',', '.')); ?>đ</td>
                    <td><?php echo e($detail->notes ?: 'Không có'); ?></td>
                    <td>
                        <form action="<?php echo e(route('cap-nhat-chi-tiet-don', $detail->order_detail_id)); ?>" method="GET" style="display:inline;">
                            <button type="submit" class="update-btn">Sửa</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('xoa-chi-tiet-don', $detail->order_detail_id)); ?>" onsubmit="return confirm('Bạn có chắc chắn muốn xóa chi tiết đơn này không?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="delete-btn">Xóa</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align: right;"><strong>Tổng tiền:</strong></td>
                <td colspan="2"><strong><?php echo e(number_format($totalPrice, 0, ',', '.')); ?>đ</strong></td>
            </tr>
        </tfoot>
    </table>

    <?php if(isset($editDetail) && $editDetail && !session('price_change_message')): ?>
        <div class="adminedit">
            <form method="GET" action="<?php echo e(route('cap-nhat-chi-tiet-don', $editDetail->order_detail_id)); ?>" id="form-select-yard-date">
                
                <div class="adminedit-form-group">
                    <label>Loại sân:</label>
                    <select name="type_id" id="type_id" onchange="document.getElementById('form-select-yard-date').submit()">
                        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type->type_id); ?>" 
                                <?php echo e(request('type_id', $editDetail->yard->type_id ?? '') == $type->type_id ? 'selected' : ''); ?>>
                                <?php echo e($type->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="adminedit-form-group">
                    <label>Tên sân:</label>
                    <select name="yard_id" id="yard_id" onchange="document.getElementById('form-select-yard-date').submit()" required>
                        <?php $__currentLoopData = $yards->where('type_id', request('type_id', $editDetail->yard->type_id)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $san): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($san->yard_id); ?>" <?php echo e(request('yard_id', $editDetail->yard_id) == $san->yard_id ? 'selected' : ''); ?>>
                                <?php echo e($san->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="adminedit-form-group">
                    <label>Ngày thuê:</label>
                    <input type="date" name="date" id="date"
                        value="<?php echo e(request('date', $editDetail->date)); ?>"
                        required onchange="document.getElementById('form-select-yard-date').submit()"
                        min="<?php echo e(date('Y-m-d')); ?>">
                </div>
            </form>

            
            <form method="POST" action="<?php echo e(route('update.order_detail', $editDetail->order_detail_id)); ?>">
                <?php echo csrf_field(); ?>

                <input type="hidden" name="yard_id" value="<?php echo e(request('yard_id', $editDetail->yard_id)); ?>">
                <input type="hidden" name="date" value="<?php echo e(request('date', $editDetail->date)); ?>">
                <div class="adminedit-form-group">
                    <label>Khung giờ:</label>
                    <select name="time" id="time" required onchange="updatePrice()">
                        <?php $__currentLoopData = $timesForSelectedDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($time->time); ?>" data-price="<?php echo e($time->price); ?>"
                                <?php echo e(old('time', $editDetail->time ?? '') == $time->time ? 'selected' : ''); ?>>
                                <?php echo e($time->time); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="adminedit-form-group">
                    <label>Giá tiền:</label>
                    <input type="text" id="price_display" value="" disabled>
                    <input type="hidden" name="price" id="price" value="">
                </div>

                <div class="adminedit-form-group">
                    <label>Ghi chú:</label><br>
                    <textarea name="notes" rows="3"><?php echo e(old('notes', $editDetail->notes ?? '')); ?></textarea>
                </div>

                <div class="adminedit-button">
                    <button class="update-btn" type="submit">Cập nhật</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <script>
        // Hàm cập nhật giá khi chọn khung giờ
        function updatePrice() {
            const timeSelect = document.getElementById('time');
            const selectedOption = timeSelect.options[timeSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            document.getElementById('price_display').value = parseInt(price).toLocaleString('vi-VN') + 'đ';
            document.getElementById('price').value = price;
        }

        // Khi load trang, hiển thị giá khung giờ đầu tiên
        window.onload = function () {
            updatePrice();
        };
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/orders/update.blade.php ENDPATH**/ ?>