

<?php $__env->startSection('title', 'Cập nhật chi tiết đơn đặt sân'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('price_change_message')): ?>
    <script>alert("<?php echo e(session('price_change_message')); ?>");</script>
<?php endif; ?>

<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<h2>Chi tiết đơn đặt sân</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="<?php echo e(route('quan-ly-don-dat-san')); ?>">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<?php
    $currentUser = auth()->user();

    // Kiểm tra xem có ít nhất 1 chi tiết mà user có quyền chỉnh sửa
    $hasEditPermission = $order->orderDetails->contains(function($detail) use ($currentUser) {
        return $detail->yard && (
            ($currentUser->role == 0 && $detail->yard->user_id == $currentUser->user_id) ||
            ($currentUser->role == 2 && $detail->yard->user_id == $currentUser->user_id)
            // role 3 không được thao tác trên trang này
        );
    });

    // Tính tổng tiền
    $totalPrice = $order->orderDetails->sum('price');
?>

<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Ngày thuê</th>
            <th>Loại sân</th>
            <th>Tên sân</th>
            <th>Khung giờ</th>
            <th>Giá (đ)</th>
            <th>Ghi chú</th> 
            <?php if($hasEditPermission): ?>
                <th colspan="2">Tùy chọn</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $canEdit = $detail->yard && (
                    ($currentUser->role == 0 && $detail->yard->user_id == $currentUser->user_id) ||
                    ($currentUser->role == 2 && $detail->yard->user_id == $currentUser->user_id)
                );
            ?>

            <tr>
                <td><?php echo e($loop->iteration); ?></td>
                <td><?php echo e(\Carbon\Carbon::parse($detail->date)->format('d/m/Y')); ?></td>
                <td><?php echo e($detail->yard->type->name ?? 'Loại sân không tồn tại'); ?></td>
                <td><?php echo e($detail->yard->name ?? 'Sân không tồn tại'); ?></td>
                <td><?php echo e(optional($detail->time)->time ?? $detail->time); ?></td>
                <td><?php echo e(number_format($detail->price, 0, ',', '.')); ?>đ</td>

                
                <td>
                    <?php if($detail->notes): ?>
                        <?php $__currentLoopData = array_chunk(explode(' ', $detail->notes), 8); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e(implode(' ', $chunk)); ?><br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        Không có
                    <?php endif; ?>
                </td>

                <?php if($hasEditPermission): ?>
                    <?php if($canEdit): ?>
                        <td>
                            <form action="<?php echo e(route('cap-nhat-chi-tiet-don', $detail->order_detail_id)); ?>" method="GET" style="display:inline;">
                                <button type="submit" class="update-btn">Sửa</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="<?php echo e(route('xoa-chi-tiet-don', $detail->order_detail_id)); ?>" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa chi tiết đơn này không?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                        </td>
                    <?php else: ?>
                        <td colspan="2"></td>
                    <?php endif; ?>
                <?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>

    <tfoot>
        <tr>
            <td colspan="<?php echo e($hasEditPermission ? 7 : 6); ?>" style="text-align: right;"><strong>Tổng tiền:</strong></td>
            <td colspan="<?php echo e($hasEditPermission ? 2 : 1); ?>"><strong><?php echo e(number_format($totalPrice, 0, ',', '.')); ?>đ</strong></td>
        </tr>
    </tfoot>
</table>


<?php if(isset($editDetail) && $editDetail): ?>
<div class="adminedit">
    
    <form method="GET" action="<?php echo e(route('cap-nhat-chi-tiet-don', $editDetail->order_detail_id)); ?>" id="form-select-yard-date">
        <div class="adminedit-form-group">
            <label>Loại sân:</label>
            <select name="type_id" id="typeSelect">
                <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->type_id); ?>" <?php echo e($selectedType == $type->type_id ? 'selected' : ''); ?>>
                        <?php echo e($type->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="adminedit-form-group">
            <label>Tên sân:</label>
            <select name="yard_id" id="yardSelect">
                <option value="">Chọn sân</option>
                <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $san): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($san->yard_id); ?>" <?php echo e($selectedYard == $san->yard_id ? 'selected' : ''); ?>>
                        <?php echo e($san->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>

        <div class="adminedit-form-group">
            <label>Ngày thuê:</label>
            <input type="date" name="date" value="<?php echo e($selectedDate); ?>" min="<?php echo e(date('Y-m-d')); ?>" onchange="this.form.submit()">
        </div>
    </form>

    
    <form method="POST" action="<?php echo e(route('update.order_detail', $editDetail->order_detail_id)); ?>">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="yard_id" value="<?php echo e($selectedYard); ?>">
        <input type="hidden" name="date" value="<?php echo e($selectedDate); ?>">

        <div class="adminedit-form-group">
            <label>Khung giờ:</label>
            <select name="time" id="time" required onchange="updatePrice()" <?php echo e(!$selectedYard ? 'disabled' : ''); ?>>
                <option value="">Chọn khung giờ</option>
                <?php if($selectedYard && $selectedDate): ?>
                    <?php $__currentLoopData = $timesForSelectedDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($time['price'] !== null): ?>
                            <option value="<?php echo e($time['time']); ?>" data-price="<?php echo e($time['price']); ?>"
                                <?php echo e($editDetail->time == $time['time'] ? 'selected' : ''); ?>>
                                <?php echo e($time['time']); ?>

                            </option>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="adminedit-form-group">
            <label>Giá tiền:</label>
            <input type="text" id="price_display" disabled value="<?php echo e($selectedYard ? number_format($editDetail->price,0,',','.') .'đ' : ''); ?>">
            <input type="hidden" name="price" id="price" value="<?php echo e($selectedYard ? $editDetail->price : ''); ?>">
        </div>

        
        <div class="adminedit-form-group">
            <label>Ghi chú:</label>
            <input type="text" name="notes" value="<?php echo e(old('notes', $editDetail->notes)); ?>" placeholder="Nhập ghi chú (nếu có)">
        </div>

        <div class="adminedit-button">
            <button type="submit" class="update-btn" <?php echo e(!$selectedYard ? 'disabled' : ''); ?>>Cập nhật thông tin</button>
        </div>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#typeSelect').on('change', function() {
        let typeId = $(this).val();
        $('#yardSelect').html('<option value="">Chọn sân</option>');

        if(typeId) {
            $.ajax({
                url: '/admin/yards-by-type/' + typeId,
                type: 'GET',
                success: function(data) {
                    data.forEach(function(yard) {
                        $('#yardSelect').append('<option value="'+yard.yard_id+'">'+yard.name+'</option>');
                    });
                },
                error: function() { alert('Không thể load sân!'); }
            });
        }
    });

    window.updatePrice = function() {
        const timeSelect = document.getElementById('time');
        const selectedOption = timeSelect?.options[timeSelect.selectedIndex];
        const price = selectedOption?.getAttribute('data-price') || '';
        document.getElementById('price_display').value = price ? parseInt(price).toLocaleString('vi-VN') + 'đ' : '';
        document.getElementById('price').value = price || '';
    };

    if("<?php echo e($selectedYard); ?>") updatePrice();
});
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/orders/update.blade.php ENDPATH**/ ?>