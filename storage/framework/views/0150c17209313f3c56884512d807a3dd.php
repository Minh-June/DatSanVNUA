

<?php $__env->startSection('title', 'Danh sách các cửa hàng'); ?>

<?php $__env->startSection('content'); ?>
<div id="content" class="order-section">
    <h2 class="order-heading">MUA SẮM ĐỒ THỂ THAO</h2>

    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <!-- Form tìm kiếm -->
    <form method="GET" action="<?php echo e(route('danh-sach-cua-hang')); ?>">
        <div class="news-method">
            <div class="news-search">
                <label for="searchStore">Tìm kiếm:</label>
                <input type="text" id="searchStore" name="name" placeholder="Nhập cửa hàng, sản phẩm..."
                    value="<?php echo e(request('name')); ?>">
                <button type="submit" class="order-football-btn" style="font-size: 18px;">Tìm kiếm</button>
            </div>
        </div>
    </form>
    
    <!-- Danh sách cửa hàng (Giao diện mới) -->
    <div class="store-list-container">
        <?php $__empty_1 = true; $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="store-card">
                <!-- Header của Card -->
                <div class="store-header" onclick="window.location='<?php echo e(route('chi-tiet-cua-hang', $store->store_id)); ?>'">
                    <div class="store-avatar">
                        <?php if($store->user && $store->user->image): ?>
                            <img src="<?php echo e(asset('storage/' . $store->user->image)); ?>" alt="<?php echo e($store->name); ?>">
                        <?php else: ?>
                            <img src="<?php echo e(asset('images/default-avatar.png')); ?>" alt="Không có ảnh">
                        <?php endif; ?>
                    </div>
                    <div class="store-info">
                        <h3 class="store-name"><?php echo e($store->name); ?></h3>
                        <p class="store-owner">
                            <?php echo e($store->user ? $store->user->fullname : 'Chưa xác định'); ?>

                        </p>
                    </div>
                </div>

                <!-- Gallery sản phẩm  -->
                <div class="store-products-gallery">
                    <?php if($store->products && $store->products->count() > 0): ?>
                        <?php $__currentLoopData = $store->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                // Lấy size có giá thấp nhất
                                $defaultSize = $product->sizes->sortBy('price')->first();
                                $displayPrice = $defaultSize->price ?? $product->price;
                            ?>
                            <div class="product-item" onclick="window.location='<?php echo e(route('chi-tiet-san-pham', $product->product_id)); ?>'">
                                <?php if($product->images->count() > 0): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->images->first()->image)); ?>" alt="<?php echo e($product->name); ?>">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('images/no-image.png')); ?>" alt="Không có ảnh">
                                <?php endif; ?>
                                <div class="product-info">
                                    <p class="product-name"><?php echo e($product->name); ?></p>
                                    <p class="product-price"><?php echo e(number_format($displayPrice, 0, ',', '.')); ?>đ</p>
                                    <form method="POST" action="<?php echo e(route('luu-san-pham-storesboard')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="product_id" value="<?php echo e($product->product_id); ?>">
                                        <input type="hidden" name="store_id" value="<?php echo e($product->store_id); ?>">
                                        <input type="hidden" name="name" value="<?php echo e($product->name); ?>">
                                        <input type="hidden" name="price" value="<?php echo e($displayPrice); ?>">
                                        <input type="hidden" name="image" value="<?php echo e($product->images->first()->image ?? ''); ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="size" value="<?php echo e($defaultSize->name ?? ''); ?>">
                                        <input type="hidden" name="product_size_id" value="<?php echo e($defaultSize->product_size_id ?? ''); ?>">
                                        <button type="submit" class="store-add-btn">Thêm vào giỏ hàng</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="no-products">Chưa có sản phẩm</p>
                    <?php endif; ?>
                </div>

                <!-- Nút hành động  -->
                <a href="<?php echo e(route('chi-tiet-cua-hang', $store->store_id)); ?>" class="store-action-btn">Đi đến cửa hàng</a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="text-align:center; margin:169px 0;">Hiện chưa có cửa hàng nào hoạt động.</p>
        <?php endif; ?>
    </div>

    <!-- Pagination custom -->
    <ul class="pagination">
        
        <li class="pagination-item <?php echo e($stores->onFirstPage() ? 'disabled' : ''); ?>">
            <a href="<?php echo e($stores->previousPageUrl() ?? '#'); ?>" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-left"></i>
            </a>
        </li>

        <!-- Page numbers -->
        <?php for($i = 1; $i <= $stores->lastPage(); $i++): ?>
            <?php if($i <= 5 || $i == $stores->lastPage()): ?>
                <li class="pagination-item <?php echo e($stores->currentPage() == $i ? 'pagination-item--active' : ''); ?>">
                    <a href="<?php echo e($stores->url($i)); ?>" class="pagination-item__link"><?php echo e($i); ?></a>
                </li>
                <?php if($i == 5 && $stores->lastPage() > 6): ?>
                    <li class="pagination-item"><span class="pagination-item__link">...</span></li>
                <?php endif; ?>
            <?php endif; ?>
        <?php endfor; ?>

        <!-- Next -->
        <li class="pagination-item <?php echo e($stores->hasMorePages() ? '' : 'disabled'); ?>">
            <a href="<?php echo e($stores->nextPageUrl() ?? '#'); ?>" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-right"></i>
            </a>
        </li>
    </ul>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/storesboard.blade.php ENDPATH**/ ?>