

<?php $__env->startSection('title', 'Cáº­p nháº­t khung giá» sĂ¢n'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>
            alert("<?php echo e(session('error')); ?>");
        </script>
    <?php endif; ?>

    <h3>Cáº­p nháº­t khung giá»</h3>

    <!-- Form cáº­p nháº­t khung giá» -->
    <div class="adminedit">
        <form action="<?php echo e(route('update.time', ['time_id' => $time->time_id])); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <label for="yard_id">Chá»n sĂ¢n:</label>
            <select id="yard_id" name="yard_id" required>
                <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($yard->yard_id); ?>" <?php echo e($yard->yard_id == $time->yard_id ? 'selected' : ''); ?>>
                        <?php echo e($yard->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <br>

            <label for="time">Khung giá»:</label>
            <input type="text" id="time" name="time" value="<?php echo e($time->time); ?>" required>
            <br>

            <label for="price">GiĂ¡ (VNÄ):</label>
            <input type="number" id="price" name="price" value="<?php echo e($time->price); ?>" required min="0">
            <br>

            <label for="date">NgĂ y Ă¡p dá»¥ng:</label>
            <input type="date" id="date" name="date" value="<?php echo e($time->date); ?>" required>
            <br>

            <button class="update-btn" type="submit">Cáº­p nháº­t khung giá»</button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/timeyards/update.blade.php ENDPATH**/ ?>
