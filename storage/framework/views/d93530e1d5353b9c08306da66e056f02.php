

<?php $__env->startSection('title', 'Tin tức thể thao'); ?>

<?php $__env->startSection('content'); ?>
<div id="content" class="order-section">
    <h2 class="order-heading">TIN TỨC THỂ THAO</h2>

    <form method="GET" action="<?php echo e(route('tin-tuc')); ?>">
        <div class="news-method">
            <div class="news-filter">
                <label for="newsType"">Loại tin tức:</label>
                <select id="newsType" name="news_type_id">
                    <option value="">Tất cả</option>
                    <?php $__currentLoopData = $newsTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($type->news_type_id); ?>" <?php echo e(request('news_type_id') == $type->news_type_id ? 'selected' : ''); ?>>
                            <?php echo e($type->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button type="submit" class="order-football-btn" style="font-size: 18px;">Tìm kiếm</button>
            </div>

            <div class="news-search">
                <label for="searchTitle">Tìm kiếm:</label>
                <input type="text" id="searchTitle" name="title" placeholder="Nhập tiêu đề bài viết..." 
                    value="<?php echo e(request('title')); ?>">
                <button type="submit" class="order-football-btn" style="font-size: 18px;">Tìm kiếm</button>
            </div>
        </div>
    </form>

    <div class="news-list">
        <?php $__empty_1 = true; $__currentLoopData = $newsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $news): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $firstImage = $news->contents->first()?->image;
                $newsUrl = route('chi-tiet-tin-tuc', $news->news_id);
            ?>
            <div class="news-item" onclick="window.location='<?php echo e($newsUrl); ?>'" style="cursor:pointer;">
                <div class="news-thumb">
                    <?php if($firstImage): ?>
                        <img src="<?php echo e(asset($firstImage)); ?>" alt="<?php echo e($news->title); ?>">
                    <?php else: ?>
                        <img src="<?php echo e(asset('images/no-image.png')); ?>" alt="Không có ảnh">
                    <?php endif; ?>
                </div>

                <div class="news-info">
                    <h3 class="news-title"><?php echo e($news->title); ?></h3>
                    <p class="news-meta">
                        <span>Loại tin tức: <?php echo e($news->type->name); ?></span> |
                        <span>Người đăng: <?php echo e($news->user->fullname ?? 'Ẩn danh'); ?></span> |
                        <span>Ngày: <?php echo e(date('d/m/Y', strtotime($news->post_at))); ?></span>
                    </p>
                    <p class="news-content"><?php echo strip_tags($news->contents->first()?->content ?? ''); ?></p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="text-align:center; margin:169px 0;">Hiện chưa có bài đăng nào</p>
        <?php endif; ?>
    </div>

    
    <ul class="pagination">
        
        <li class="pagination-item <?php echo e($newsList->onFirstPage() ? 'disabled' : ''); ?>">
            <a href="<?php echo e($newsList->previousPageUrl() ?? '#'); ?>" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-left"></i>
            </a>
        </li>

        
        <?php for($i = 1; $i <= $newsList->lastPage(); $i++): ?>
            <?php if($i <= 5 || $i == $newsList->lastPage()): ?>
                <li class="pagination-item <?php echo e($newsList->currentPage() == $i ? 'pagination-item--active' : ''); ?>">
                    <a href="<?php echo e($newsList->url($i)); ?>" class="pagination-item__link"><?php echo e($i); ?></a>
                </li>
                <?php if($i == 5 && $newsList->lastPage() > 6): ?>
                    <li class="pagination-item"><span class="pagination-item__link">...</span></li>
                <?php endif; ?>
            <?php endif; ?>
        <?php endfor; ?>

        
        <li class="pagination-item <?php echo e($newsList->hasMorePages() ? '' : 'disabled'); ?>">
            <a href="<?php echo e($newsList->nextPageUrl() ?? '#'); ?>" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-right"></i>
            </a>
        </li>
    </ul>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/newsboard.blade.php ENDPATH**/ ?>