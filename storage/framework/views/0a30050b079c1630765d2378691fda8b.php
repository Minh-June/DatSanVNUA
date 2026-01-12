

<?php $__env->startSection('title','Quản lý chi tiết đơn thuê cố định theo tháng'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>
<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<h2>Chi tiết đơn cố định</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="<?php echo e(route('quan-ly-don-dat-san-co-dinh')); ?>">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Loại sân</th>
            <th>Sân</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Thứ</th>
            <th>Khung giờ</th>
            <th>Giá (đ)</th>
            <?php if(auth()->user()->role != 3): ?>
                <th>Tùy chọn</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php $stt = 1; ?>
        <tr>
            <td><?php echo e($stt++); ?></td>
            <td><?php echo e($order->yard->type->name ?? ''); ?></td>
            <td><?php echo e($order->yard->name ?? ''); ?></td>
            <td><?php echo e(\Carbon\Carbon::parse($order->from_date)->format('d/m/Y')); ?></td>
            <td><?php echo e(\Carbon\Carbon::parse($order->to_date)->format('d/m/Y')); ?></td>
            <td>
                <?php $__currentLoopData = explode(',', $order->weekday); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e(['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật'][$day] ?? ''); ?><br>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </td>
            <td><?php echo e(\Carbon\Carbon::parse($order->start)->format('H:i')); ?> - <?php echo e(\Carbon\Carbon::parse($order->end)->format('H:i')); ?></td>
            <td><?php echo e(number_format($order->price, 0, ',', '.')); ?>đ</td>
            <?php if(auth()->user()->role != 3): ?>
            <td>
                <form action="<?php echo e(route('cap-nhat-chi-tiet-don-dat-san-co-dinh', $order->month_rent_id)); ?>" method="GET">
                    <input type="hidden" name="edit" value="1">
                    <button type="submit" class="update-btn">Sửa</button>
                </form>
            </td>
            <?php endif; ?>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" style="text-align: right;"><strong>Tổng tiền:</strong></td>
            <td colspan="2"><strong><?php echo e(number_format($order->price, 0, ',', '.')); ?>đ</strong></td>
        </tr>
    </tfoot>
</table>

<?php if(isset($editDetail) && $editDetail): ?>
<h2>Cập nhật thông tin chi tiết</h2>

<div class="month-rent-content">

    
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul style="margin:0; padding-left:20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('update.fixedorder_detail', ['order_id' => $editDetail->month_rent_id])); ?>">
        <?php echo csrf_field(); ?>

        
        <div class="form-group">
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

        
        <div class="form-group">
            <label>Ngày bắt đầu:</label>
            <input type="date" name="from_date" value="<?php echo e(old('from_date', $from_date)); ?>" min="<?php echo e(date('Y-m-d')); ?>">
        </div>

        
        <div class="form-group">
            <label>Ngày kết thúc:</label>
            <input type="date" name="to_date" value="<?php echo e(old('to_date', $to_date)); ?>" min="<?php echo e(date('Y-m-d')); ?>">
        </div>

        
        <div class="form-group">
            <label>Ngày trong tuần:</label>
            <div class="weekday-list">
                <?php $days = ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật']; ?>
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="weekday-item <?php echo e(in_array($index, $selectedWeekdays) ? 'selected' : ''); ?>"
                          data-value="<?php echo e($index); ?>" onclick="toggleWeekday(this)">
                        <?php echo e($day); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <input type="hidden" name="weekdays" id="selectedWeekdays" value="<?php echo e(implode(',', $selectedWeekdays)); ?>">
        </div>

        
        <div class="form-group">
            <label>Giờ bắt đầu:</label>
            <input type="time" name="start" value="<?php echo e(\Carbon\Carbon::parse($time_from)->format('H:i')); ?>" min="06:00" max="22:30">
        </div>
        <div class="form-group">
            <label>Giờ kết thúc:</label>
            <input type="time" name="end" value="<?php echo e(\Carbon\Carbon::parse($time_to)->format('H:i')); ?>" min="06:30" max="22:30">
        </div>

        
        <div class="form-group">
            <label>Giá tiền (đ):</label>
            <input type="text" id="price_display" value="<?php echo e(number_format($price,0,',','.')); ?>">
            <input type="hidden" name="price" id="price" value="<?php echo e($price); ?>">
        </div>

        <button type="submit" style="font-size: 18px; margin-bottom:30px;" class="monthly-submit-btn">Cập nhật thông tin</button>
    </form>
</div>

<style>
    .month-rent-content {
        width: 54%;
        margin: 30px auto;
    }

    .month-rent-content .weekday-item {
        padding: 8px 12px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {

    // Toggle chọn ngày trong tuần
    function updateWeekdaysInput() {
        let selected = [];
        $('.weekday-item.selected').each(function() {
            selected.push($(this).data('value'));
        });
        $('#selectedWeekdays').val(selected.join(','));
    }

    window.toggleWeekday = function(el) {
        $(el).toggleClass('selected');
        updateWeekdaysInput();
    };

    // Đồng bộ giá tiền nhập tay với hidden input
    $('#price_display').on('input', function(){
        let val = $(this).val().replace(/\D/g,'');
        $('#price').val(val);
    });

    // Cập nhật hidden weekdays khi load
    updateWeekdaysInput();
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/fixedorder/update.blade.php ENDPATH**/ ?>