<form method="POST" action="<?php echo e($action); ?>" id="<?php echo e($form_id ?? 'orderForm'); ?>" onsubmit="<?php echo e($onsubmit ?? ''); ?>">
    <?php echo csrf_field(); ?>

    <?php if(!($is_client ?? false)): ?>
        
        <label for="name">Há» vĂ  tĂªn:</label>
        <input type="text" name="name" id="name" value="<?php echo e(old('name', $name ?? '')); ?>" required><br>

        <label for="phone">Sá»‘ Ä‘iá»‡n thoáº¡i:</label>
        <input type="text" name="phone" id="phone" value="<?php echo e(old('phone', $phone ?? '')); ?>" required><br>
    <?php else: ?>
        
        <input type="hidden" name="name" value="<?php echo e($name ?? ''); ?>">
        <input type="hidden" name="phone" value="<?php echo e($phone ?? ''); ?>">
    <?php endif; ?>

    <?php if(isset($sans)): ?>
        <label>Chá»n sĂ¢n:</label>
        <select name="yard_id" id="yard_id" required onchange="onYardChange()">
            <option value="">-- Chá»n sĂ¢n --</option>
            <?php $__currentLoopData = $sans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $san): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($san->yard_id); ?>" <?php echo e((old('yard_id', $yard_id ?? '') == $san->yard_id) ? 'selected' : ''); ?>>
                    <?php echo e($san->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select><br>
    <?php else: ?>
        <input type="hidden" name="yard_id" value="<?php echo e($yard_id ?? ''); ?>">
    <?php endif; ?>

    <label for="date">Chá»n ngĂ y:</label>
    <input type="date" name="date" id="date"
           value="<?php echo e(old('date', $selected_date ?? date('Y-m-d'))); ?>"
           min="<?php echo e(date('Y-m-d')); ?>"
           onchange="onDateChange()" required><br>

    <label>Chá»n giá»:</label>
    <div id="time_slots_container" class="time-slots">
        <?php if(!empty($times)): ?>
            <?php $__currentLoopData = $times; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $disabled = false;
                    if (!empty($adminBookedTimes) && in_array($slot->time, $adminBookedTimes)) {
                        $disabled = true;
                    }
                    if (!empty($sessionBookedTimes) && in_array($slot->time, $sessionBookedTimes)) {
                        $disabled = true;
                    }
                    $selected = !empty($selected_times) && in_array($slot->time, (array) $selected_times);
                ?>
                <button type="button"
                        class="btn-time <?php echo e($disabled ? 'booked' : ''); ?> <?php echo e($selected ? 'selected' : ''); ?>"
                        data-time="<?php echo e($slot->time); ?>"
                        data-price="<?php echo e($slot->price); ?>"
                        <?php echo e($disabled ? 'disabled' : ''); ?>>
                    <?php echo e($slot->time); ?>

                </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    </div><br>

    <input type="hidden" name="selected_times" id="selected_times" value="<?php echo e(is_array(old('selected_times')) ? implode(',', old('selected_times')) : ($selected_times ?? '')); ?>">
    <input type="hidden" name="total_price" id="total_price_input" value="<?php echo e(old('total_price', $total_price ?? 0)); ?>">
    <input type="hidden" name="user_id" value="<?php echo e($user_id ?? ''); ?>">
    <input type="hidden" name="continue_booking" id="continue_booking" value="">

    <label for="notes">Ghi chĂº:</label><br>
    <textarea name="notes" id="notes" rows="3"><?php echo e(old('notes', $notes ?? '')); ?></textarea><br>

    <label>GiĂ¡ tiá»n: <span id="total_price_display"><?php echo e(number_format(old('total_price', $total_price ?? 0), 0, ',', '.')); ?> VND</span></label><br>

    <button type="submit" class="order-button"><?php echo e($button_text ?? 'Äáº·t sĂ¢n'); ?></button>
</form>
<?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/layouts/order-form.blade.php ENDPATH**/ ?>
