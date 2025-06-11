

<?php $__env->startSection('title', 'Quáº£n lĂ½ khung giá» cho thuĂª'); ?>

<?php $__env->startSection('content'); ?>
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

    <h3>Quáº£n lĂ½ khung giá» - <?php echo e($times->first()->yard->name ?? ''); ?></h3>

    <div class="admin-top-bar">
        <?php if(request('yard_id')): ?>
            <div class="admin-search">
                <form method="GET" action="<?php echo e(route('quan-ly-thoi-gian-san')); ?>">
                    <input type="hidden" name="yard_id" value="<?php echo e(request('yard_id')); ?>">
                    <label for="date">Chá»n ngĂ y:</label>
                    <input type="date" id="date" name="date" value="<?php echo e(request('date', date('Y-m-d'))); ?>">
                    <button class="admin-search-btn" type="submit">TĂ¬m kiáº¿m</button>
                </form>
            </div>
        <?php endif; ?>
        <div class="admin-add-btn">
            <a href="<?php echo e(route('them-thoi-gian-san')); ?>">ThĂªm khung giá» cho thuĂª</a>
        </div>
    </div>

        <!-- Hiá»ƒn thá»‹ báº£ng dá»¯ liá»‡u khi Ä‘Ă£ chá»n sĂ¢n vĂ  lá»c theo ngĂ y -->
        <table id='ListCustomers'>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Khung giá»</th>
                    <th>GiĂ¡ (VNÄ)</th>
                    <th colspan="2">TĂ¹y chá»n</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $times; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($time->time); ?></td>
                        <td><?php echo e(number_format($time->price, 0, ',', '.')); ?></td>
                        <td>
                            <form method="GET" action="<?php echo e(route('cap-nhat-thoi-gian-san', ['time_id' => $time->time_id])); ?>">
                                <button type="submit" class="update-btn">Sá»­a</button>
                            </form>
                        </td>
                        <td>
                        <form method="POST" action="<?php echo e(route('xoa-thoi-gian-san', ['time_id' => $time->time_id, 'yard_id' => request('yard_id')])); ?>" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c cháº¯n muá»‘n xĂ³a khung giá» nĂ y?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="update-btn">XĂ³a</button>
                        </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/timeyards/index.blade.php ENDPATH**/ ?>
