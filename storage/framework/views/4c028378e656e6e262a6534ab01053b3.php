

<?php $__env->startSection('title', $news->title ?? 'Chi tiết tin tức thể thao'); ?>

<?php $__env->startSection('content'); ?>
<div id="content" class="order-section">
    <h2 class="order-heading">TIN TỨC THỂ THAO</h2>
    <div class="news-detail-wrapper">

        
        <div class="news-detail-left">
            <div class="news-header-detail">
                <h1 class="news-title-detail"><?php echo e($news->title); ?></h1>
                <p class="news-meta-detail">
                    <span>Loại tin tức: <?php echo e($news->type->name); ?></span>
                    | 
                    <span>Người đăng: <?php echo e($news->user->fullname ?? 'Ẩn danh'); ?></span>
                    | 
                    <span>Ngày đăng: <?php echo e(date('d/m/Y', strtotime($news->post_at))); ?></span>
                </p>
            </div>
            <div class="news-contents">
                <?php $__currentLoopData = $news->contents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $content): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <?php if($content->content): ?>
                        <div class="news-text">
                            <?php echo nl2br(e($content->content)); ?>

                        </div>
                    <?php endif; ?>

                    
                    <?php if($content->image): ?>
                        <div class="news-image">
                            <div class="image-wrapper">
                                <img src="<?php echo e(asset($content->image)); ?>" alt="Hình ảnh bài viết">
                                <?php if($content->note): ?>
                                    <p><?php echo e($content->note); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <div class="news-detail-right">
            <h3>Tin tức liên quan</h3>
            <ul>
                <?php $__currentLoopData = $relatedNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li style="margin-bottom: 20px; text-align: center; cursor: pointer;"
                    onclick="window.location='<?php echo e(route('chi-tiet-tin-tuc', $related->news_id)); ?>'">
                    <?php if($related->contents->first()?->image): ?>
                        <img src="<?php echo e(asset($related->contents->first()->image)); ?>" alt="Hình liên quan" class="news-image-right">
                    <?php endif; ?>
                    <a><?php echo e(Str::limit($related->title, 58)); ?></a>
                    <p><?php echo e(date('d/m/Y', strtotime($related->post_at))); ?></p>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>

            <h3>Sản phẩm liên quan</h3>
            <ul class="related-products-list">
                <?php $__currentLoopData = $relatedProducts ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="related-product-item" onclick="window.location='<?php echo e(route('chi-tiet-san-pham', $product->product_id)); ?>'">
                        <div class="shop-info">
                            <?php if($product->store->user->image): ?>
                                <img src="<?php echo e(asset('storage/' . $product->store->user->image)); ?>" alt="Ảnh chủ shop" class="shop-image">
                            <?php endif; ?>
                            <div class="shop-name">
                                <strong><?php echo e(Str::limit($product->store->name ?? 'Không xác định', 38)); ?></strong>
                            </div>
                        </div>

                        <?php if($product->images->first()?->image): ?>
                            <img src="<?php echo e(asset('storage/' . $product->images->first()->image)); ?>" alt="Hình sản phẩm" class="product-image">
                        <?php endif; ?>

                        <a href="<?php echo e(route('chi-tiet-san-pham', $product->product_id)); ?>" class="product-name">
                            <?php echo e(Str::limit($product->name, 62)); ?>

                        </a>

                        <p class="product-price"><?php echo e(number_format($product->price,0,'','.')); ?>đ</p>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
    
    <div class="newsdetail-btn">
        <a href="<?php echo e(route('tin-tuc')); ?>">
            <i class="fa-solid fa-arrow-left"></i> Danh sách tin tức
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/newsdetail.blade.php ENDPATH**/ ?>