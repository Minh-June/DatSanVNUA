

<?php $__env->startSection('title', 'Quáº£n lĂ½ hĂ¬nh áº£nh sĂ¢n'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o thĂ nh cĂ´ng -->
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

    <h3>
        Quáº£n lĂ½ hĂ¬nh áº£nh 
        <?php if(isset($selectedYard)): ?>
            - <?php echo e($selectedYard->name); ?>

        <?php endif; ?>
    </h3>

    <div class="admin-top-bar">
        <div class="admin-search"></div>

        <div class="admin-add-btn">
            <a href="<?php echo e(route('them-hinh-anh-san')); ?>">ThĂªm hĂ¬nh áº£nh sĂ¢n</a>
        </div>
    </div>

    <!-- Hiá»ƒn thá»‹ báº£ng hĂ¬nh áº£nh khi Ä‘Ă£ chá»n sĂ¢n -->
    <table id='ListCustomers'>
        <thead>
            <tr>
                <th>STT</th>
                <th>HĂ¬nh áº£nh</th>
                <th colspan="2">TĂ¹y chá»n</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $selectedYard->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td>
                        <img src="<?php echo e(asset('storage/' . $image->image)); ?>" alt="HĂ¬nh áº£nh" class="admin-image">
                    </td>
                    <td>
                        <form action="<?php echo e(route('cap-nhat-hinh-anh-san', ['image_id' => $image->image_id])); ?>" method="GET">
                            <button type="submit" class="update-btn">Sá»­a</button>
                        </form>
                    </td>
                    <td>
                        <form action="<?php echo e(route('xoa-hinh-anh-san', ['image_id' => $image->image_id])); ?>" method="POST" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c cháº¯n muá»‘n xĂ³a hĂ¬nh áº£nh nĂ y?');">
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/imgyards/index.blade.php ENDPATH**/ ?>
