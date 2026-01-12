

<?php $__env->startSection('title', 'Tin tức thể thao'); ?>

<?php $__env->startSection('content'); ?>
<?php if($paginator->hasPages()): ?>
    <ul class="pagination news__pagination">
        
        <?php if($paginator->onFirstPage()): ?>
            <li class="pagination-item disabled">
                <span class="pagination-item__link">
                    <i class="fa-solid fa-angle-left"></i>
                </span>
            </li>
        <?php else: ?>
            <li class="pagination-item">
                <a href="<?php echo e($paginator->previousPageUrl()); ?>" class="pagination-item__link">
                    <i class="fa-solid fa-angle-left"></i>
                </a>
            </li>
        <?php endif; ?>

        
        <?php $__currentLoopData = $elements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(is_string($element)): ?>
                <li class="pagination-item disabled"><span class="pagination-item__link"><?php echo e($element); ?></span></li>
            <?php endif; ?>

            <?php if(is_array($element)): ?>
                <?php $__currentLoopData = $element; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($page == $paginator->currentPage()): ?>
                        <li class="pagination-item pagination-item--active">
                            <span class="pagination-item__link"><?php echo e($page); ?></span>
                        </li>
                    <?php else: ?>
                        <li class="pagination-item">
                            <a href="<?php echo e($url); ?>" class="pagination-item__link"><?php echo e($page); ?></a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if($paginator->hasMorePages()): ?>
            <li class="pagination-item">
                <a href="<?php echo e($paginator->nextPageUrl()); ?>" class="pagination-item__link">
                    <i class="fa-solid fa-angle-right"></i>
                </a>
            </li>
        <?php else: ?>
            <li class="pagination-item disabled">
                <span class="pagination-item__link">
                    <i class="fa-solid fa-angle-right"></i>
                </span>
            </li>
        <?php endif; ?>
    </ul>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/pagination.blade.php ENDPATH**/ ?>