

<?php $__env->startSection('title', $store->name ?? 'Trang chi tiết cửa hàng'); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/store-detail.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div id="content" class="order-section">
    <h2 class="order-heading">CỬA HÀNG</h2>

    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <!-- Thông tin chủ shop -->
    <div class="content-shop">
        <div class="store-info-header">
            <div class="store-info-left">
                <?php if($store->user && $store->user->image): ?>
                    <img src="<?php echo e(asset('storage/' . $store->user->image)); ?>" alt="Ảnh chủ shop">
                <?php else: ?>
                    <img src="<?php echo e(asset('images/default-avatar.png')); ?>" alt="Không có ảnh">
                <?php endif; ?>
                <div class="store-info-text">
                    <p><?php echo e($store->name ?? 'Shop thể thao'); ?></p>
                    <p>
                        <?php echo e($store->user ? $store->user->fullname : 'Chưa xác định'); ?>

                    </p>
                </div>
            </div>

            <div class="store-info-right">
                
                <?php if($store->user && $store->user->phonenb): ?>
                    <p class="store-phone">
                        <i class="fa-solid fa-phone" style="margin: 0 7px 10px 0;"></i>
                        Liên hệ: <?php echo e($store->user->phonenb); ?>

                    </p>
                <?php endif; ?>
                <p>
                    <i class="fa-solid fa-shop"></i>
                    Sản phẩm: <?php echo e($products->total()); ?>

                </p>
            </div>
        </div>
    </div>

    <div class="shop-layout">
        <!-- Danh mục sản phẩm -->
        <div class="shop-sidebar">
            <p class="sidebar-title">
                <i class="fa-solid fa-bars"></i>
                Danh mục sản phẩm
            </p>
            <ul class="sidebar-list">
                <li>
                    <a href="<?php echo e(route('chi-tiet-cua-hang', $store->store_id)); ?>" class="sidebar-link <?php echo e(request('type') ? '' : 'active'); ?>">Tất cả</a>
                </li>
                <?php $__currentLoopData = $productTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e(route('chi-tiet-cua-hang', [$store->store_id, 'type' => $type->product_type_id])); ?>" 
                           class="sidebar-link <?php echo e(request('type') == $type->product_type_id ? 'active' : ''); ?>">
                            <?php echo e($type->name); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="shop-main">
            <div class="shop-filter">
                <form method="GET" action="<?php echo e(route('chi-tiet-cua-hang', $store->store_id)); ?>" class="filter-form" id="filterForm">
                    <p>Tìm kiếm:</p>
                    <input type="text" name="search" placeholder="Mã sản phẩm, tên sản phẩm..." value="<?php echo e(request('search')); ?>" class="filter-input">
                    <button type="submit" class="update-btn" style="padding:7px 13px; margin-left:5px;">Tìm kiếm</button>

                    <p>Sắp xếp theo:</p>
                    <select name="sort" class="filter-select" id="sortSelect">
                        <option value="">Mức giá</option>
                        <option value="price_asc" <?php echo e(request('sort') == 'price_asc' ? 'selected' : ''); ?>>Mức giá: Thấp → Cao</option>
                        <option value="price_desc" <?php echo e(request('sort') == 'price_desc' ? 'selected' : ''); ?>>Mức giá: Cao → Thấp</option>
                    </select>
                </form>
            </div>

            <div class="store-products-gallery">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="product-item" onclick="window.location='<?php echo e(route('chi-tiet-san-pham', $product->product_id)); ?>'">
                        <?php
                            $img = $productImages[$product->name] ?? null;
                            $defaultSize = $product->sizes->sortBy('price')->first();
                            $displayPrice = $defaultSize->price ?? $product->price;
                        ?>
                        <img src="<?php echo e($img ? asset('storage/' . $img) : asset('images/no-image.png')); ?>" alt="<?php echo e($product->name); ?>">

                        <div class="product-info">
                            <p class="product-name"><?php echo e($product->name); ?></p>
                            <p class="product-price"><?php echo e(number_format($displayPrice, 0, ',', '.')); ?>đ</p>

                            <!-- Form POST thêm vào giỏ hàng -->
                            <form method="POST" action="<?php echo e(route('luu-san-pham-storedetail')); ?>">
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="no-products">Hiện chưa có sản phẩm</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <ul class="pagination">
        <li class="pagination-item <?php echo e($products->onFirstPage() ? 'disabled' : ''); ?>">
            <a href="<?php echo e($products->previousPageUrl() ?? '#'); ?>" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-left"></i>
            </a>
        </li>

        <?php for($i = 1; $i <= $products->lastPage(); $i++): ?>
            <?php if($i <= 5 || $i == $products->lastPage()): ?>
                <li class="pagination-item <?php echo e($products->currentPage() == $i ? 'pagination-item--active' : ''); ?>">
                    <a href="<?php echo e($products->url($i)); ?>" class="pagination-item__link"><?php echo e($i); ?></a>
                </li>
                <?php if($i == 5 && $products->lastPage() > 6): ?>
                    <li class="pagination-item"><span class="pagination-item__link">...</span></li>
                <?php endif; ?>
            <?php endif; ?>
        <?php endfor; ?>

        <li class="pagination-item <?php echo e($products->hasMorePages() ? '' : 'disabled'); ?>">
            <a href="<?php echo e($products->nextPageUrl() ?? '#'); ?>" class="pagination-item__link">
                <i class="pagination-item__icon fa-solid fa-angle-right"></i>
            </a>
        </li>
    </ul>
</div>

<script>
    document.getElementById('sortSelect').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/storedetail.blade.php ENDPATH**/ ?>