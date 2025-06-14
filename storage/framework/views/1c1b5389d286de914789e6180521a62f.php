

<?php $__env->startSection('title', 'Thêm đơn đặt sân'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <h2>Thêm đơn đặt sân</h2>

    <div class="adminedit">

            <div class="adminedit-form-group">
                <label>Loại sân:</label>
                <select name="yard_id" required onchange="document.getElementById('form-select-yard-date').submit()">
                    <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($yard->yard_id); ?>" <?php echo e(request('yard_id') == $yard->yard_id ? 'selected' : ''); ?>>
                            <?php echo e($yard->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        
        <form method="GET" action="<?php echo e(route('them-don-dat-san')); ?>" id="form-select-yard-date">
            
            <div class="adminedit-form-group">
                <label>Chọn sân:</label>
                <select name="yard_id" required onchange="document.getElementById('form-select-yard-date').submit()">
                    <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($yard->yard_id); ?>" <?php echo e(request('yard_id') == $yard->yard_id ? 'selected' : ''); ?>>
                            <?php echo e($yard->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="adminedit-form-group">
                <label>Ngày thuê:</label>
                <input type="date" name="date" value="<?php echo e(request('date') ?? old('date')); ?>" required onchange="document.getElementById('form-select-yard-date').submit()">
            </div>
        </form>

        
        <form method="POST" action="<?php echo e(route('luu-don-dat-san')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="yard_id" value="<?php echo e(request('yard_id')); ?>">
            <input type="hidden" name="date" value="<?php echo e(request('date')); ?>">

            <div class="adminedit-form-group">
                <label>Khung giờ:</label>
                <select name="time" id="time" required onchange="updatePrice()">
                    <?php $__currentLoopData = $timesForSelectedDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($time->time); ?>" data-price="<?php echo e($time->price); ?>">
                            <?php echo e($time->time); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="adminedit-form-group">
                <label>Thành tiền:</label>
                <input type="text" id="price_display" disabled>
                <input type="hidden" name="price" id="price">
            </div>

            <div class="adminedit-form-group">
                <label>Họ và tên:</label>
                <input type="text" name="name" value="<?php echo e(old('name')); ?>" required pattern="^[\p{L}\s]+$" title="Chỉ nhập chữ cái và khoảng trắng">
            </div>

            <div class="adminedit-form-group">
                <label>Số điện thoại:</label>
                <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required pattern="^[0-9]+$" title="Chỉ nhập số">
            </div>

            <div class="adminedit-form-group">
                <label>Ghi chú:</label><br><br>
                <textarea name="notes" rows="3"><?php echo e(old('notes')); ?></textarea>
            </div>

            <div class="adminedit-button">
                <button type="submit" class="update-btn">Xác nhận thêm đơn</button>
            </div>
        </form>
    </div>

    <script>
        function updatePrice() {
            const timeSelect = document.getElementById('time');
            const selectedOption = timeSelect.options[timeSelect.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            document.getElementById('price_display').value = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(price);
            document.getElementById('price').value = price;
        }
        window.onload = function() {
            updatePrice();
        };
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/orders/create.blade.php ENDPATH**/ ?>