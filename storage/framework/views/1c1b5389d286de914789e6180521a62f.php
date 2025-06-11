

<?php $__env->startSection('title', 'ThĂªm Ä‘Æ¡n Ä‘áº·t sĂ¢n'); ?>

<?php $__env->startSection('content'); ?>
    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <h3>ThĂªm Ä‘Æ¡n Ä‘áº·t sĂ¢n</h3>

    <div class="adminedit">
        
        <form method="GET" action="<?php echo e(route('them-don-dat-san')); ?>" id="form-select-yard-date">

            <label>Chá»n sĂ¢n:</label>
            <select name="yard_id" required onchange="document.getElementById('form-select-yard-date').submit()">
                <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($yard->yard_id); ?>" <?php echo e(request('yard_id') == $yard->yard_id ? 'selected' : ''); ?>>
                        <?php echo e($yard->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select><br>

            <label>NgĂ y thuĂª:</label>
            <input type="date" name="date" value="<?php echo e(request('date') ?? old('date')); ?>" required onchange="document.getElementById('form-select-yard-date').submit()">
        </form>

        
        <form method="POST" action="<?php echo e(route('luu-don-dat-san')); ?>">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="yard_id" value="<?php echo e(request('yard_id')); ?>">
            <input type="hidden" name="date" value="<?php echo e(request('date')); ?>">

            <label>Khung giá»:</label>
            <select name="time" id="time" required onchange="updatePrice()">
                <?php $__currentLoopData = $timesForSelectedDate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($time->time); ?>" data-price="<?php echo e($time->price); ?>">
                        <?php echo e($time->time); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select><br>

            <label>ThĂ nh tiá»n:</label>
            <input type="text" id="price_display" disabled>
            <input type="hidden" name="price" id="price"><br>

            <label>Há» vĂ  tĂªn:</label>
            <input type="text" name="name" value="<?php echo e(old('name')); ?>" required><br>

            <label>Sá»‘ Ä‘iá»‡n thoáº¡i:</label>
            <input type="text" name="phone" value="<?php echo e(old('phone')); ?>" required><br>

            <label>Ghi chĂº:</label><br><br>
            <textarea name="notes" rows="3"><?php echo e(old('notes')); ?></textarea><br>

            <button type="submit" class="update-btn">XĂ¡c nháº­n thĂªm Ä‘Æ¡n</button><br><br>
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
