

<?php $__env->startSection('title', 'Danh sĂ¡ch loáº¡i sĂ¢n'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o lá»—i -->
    <?php if(session('error')): ?>
        <script>
            alert("<?php echo e(session('error')); ?>");
        </script>
    <?php endif; ?>

    <h3>Danh sĂ¡ch loáº¡i sĂ¢n thá»ƒ thao</h3>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="<?php echo e(route('quan-ly-loai-san')); ?>">
                <label for="type_id">Chá»n loáº¡i sĂ¢n:</label>
                <select id="type_id" name="type_id">
                    <option value="">Táº¥t cáº£</option>
                    <?php $__currentLoopData = $allTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->type_id); ?>" <?php echo e(request('type_id') == $type->type_id ? 'selected' : ''); ?>>
                            <?php echo e($type->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="admin-search-btn" type="submit">TĂ¬m kiáº¿m</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a href="<?php echo e(route('them-loai-san')); ?>">ThĂªm loáº¡i sĂ¢n má»›i</a>
        </div>
    </div>
    
    <!-- Hiá»ƒn thá»‹ báº£ng dá»¯ liá»‡u -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>TĂªn loáº¡i sĂ¢n</th>
                <th colspan="2">TĂ¹y chá»n</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key + 1); ?></td>
                    <td><?php echo e($type->name); ?></td>
                    <td>
                        <form method="GET" action="<?php echo e(route('cap-nhat-loai-san', ['type_id' => $type->type_id])); ?>">
                            <button type="submit" class="update-btn">Sá»­a</button>
                        </form>
                    </td>                                      
                    <td>
                        <form method="POST" action="<?php echo e(route('xoa-loai-san', $type->type_id)); ?>" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c cháº¯n muá»‘n xĂ³a loáº¡i sĂ¢n nĂ y khĂ´ng?')">
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/types/index.blade.php ENDPATH**/ ?>
