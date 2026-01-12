

<?php $__env->startSection('title', 'Thanh toán sản phẩm'); ?>

<?php $__env->startSection('content'); ?>
<div id="content" class="order-section">
    <h2 class="order-heading">THANH TOÁN</h2>

    
    <div class="pay-method" style="margin-bottom: 20px;">
        <p>Hình thức thanh toán</p>
        <label style="margin-right: 20px;">
            <input type="radio" name="payment_method" value="offline" checked>
            Thanh toán khi nhận hàng
        </label>
        <label>
            <input type="radio" name="payment_method" value="online">
            Thanh toán trực tuyến
        </label>
    </div>
        
    <form method="POST" action="<?php echo e(route('pay.product.offline')); ?>">
        <?php echo csrf_field(); ?>
        
        <div class="cart-pay-wrapper">
            
            <div class="cart-pay-left" id="paymentInfo">
                <div class="adminedit">
                    <h2 style="text-align:center;">Thông tin thanh toán</h2>

                    <div class="adminedit-form-group" style="margin-top:20px;">
                        <label for="fullname">Họ và tên:</label>
                        <input type="text" name="fullname" value="<?php echo e(old('fullname', $user->fullname)); ?>" required>
                    </div>

                    <div class="adminedit-form-group">
                        <label for="phonenb">Số điện thoại:</label>
                        <input type="text" name="phonenb" value="<?php echo e(old('phonenb', $user->phonenb)); ?>" required>
                    </div>

                    <div class="adminedit-form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
                    </div>

                    <div class="adminedit-form-group">
                        <label for="address">Địa chỉ:</label>
                        <textarea name="address" rows="4" placeholder="Số nhà - Đường - Phường/xã - Tỉnh thành..." required><?php echo e(old('address', $user->address)); ?></textarea>
                    </div>

                    <div class="adminedit-form-group" style="margin-bottom:30px;">
                        <label for="notes">Ghi chú:<br>(tùy chọn)</label>
                        <textarea name="notes" rows="4" placeholder="Ví dụ: thời gian giao hàng..."><?php echo e(old('notes')); ?></textarea>
                    </div>
                    
                </div>
            </div>

            
            <div class="cart-pay-right">
                <?php if(count($buys) > 0): ?>
                    <table id="ListCustomers">
                        <thead>
                            <tr>
                                <th colspan="2">Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th class="online-only" style="display:none;">Tùy chọn</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = collect($buys)->groupBy('store_id'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $storeId => $storeItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $store = \App\Models\Store::find($storeId);
                                $storeName = $store->name ?? 'Shop không xác định';

                                // Gộp sản phẩm giống nhau theo name + size
                                $mergedItems = [];
                                foreach ($storeItems as $item) {
                                    $sizeName = $item['product_size_id'] ? \App\Models\ProductSize::find($item['product_size_id'])->name : '';
                                    $key = $item['name'] . '_' . $sizeName;
                                    if(isset($mergedItems[$key])) {
                                        $mergedItems[$key]['quantity'] += $item['quantity'];
                                    } else {
                                        $item['size_name'] = $sizeName; // lưu tên size để hiển thị
                                        $mergedItems[$key] = $item;
                                    }
                                }
                                $mergedItems = array_values($mergedItems);
                                $totalStorePrice = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $mergedItems));
                            ?>

                            
                            <tr class="name-shop-cart-pay" onclick="window.location='<?php echo e(route('chi-tiet-cua-hang', $storeId)); ?>'" style="cursor:pointer;">
                                <td colspan="6" class="left-align">
                                    <img src="<?php echo e($store->user && $store->user->image ? asset('storage/' . $store->user->image) : asset('images/default-avatar.png')); ?>" 
                                         alt="<?php echo e($storeName); ?>">
                                    <span><?php echo e($storeName); ?></span>
                                </td>
                            </tr>

                            
                            <?php $__currentLoopData = $mergedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td style="cursor:pointer;" onclick="window.location='<?php echo e(route('chi-tiet-san-pham', $item['product_id'])); ?>'">
                                        <img src="<?php echo e(asset('storage/' . ($item['image'] ?? 'image/football.jpg'))); ?>" width="80">
                                    </td>
                                    <td class="left-align product-name-cart" style="cursor:pointer;" onclick="window.location='<?php echo e(route('chi-tiet-san-pham', $item['product_id'])); ?>'">
                                        <?php
                                            $fullName = $item['name'] ?? '';
                                            $sizeText = !empty($item['size_name']) ? '- Size ' . $item['size_name'] : '';
                                            
                                            // Nếu có size, tách phần size riêng
                                            $words = explode(' ', $fullName);
                                            $chunks = array_chunk($words, 6);
                                        ?>

                                        
                                        <?php $__currentLoopData = $chunks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e(implode(' ', $chunk)); ?><br>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                        <?php if($sizeText): ?>
                                            <?php echo e($sizeText); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(number_format($item['price'])); ?>đ</td>
                                    <td><?php echo e($item['quantity']); ?></td>
                                    <td><?php echo e(number_format($item['price'] * $item['quantity'])); ?>đ</td>

                                    
                                    <?php if($index === 0): ?>
                                        <td class="online-only" style="text-align:center;" rowspan="<?php echo e(count($mergedItems)); ?>">
                                            <button type="button" class="order-football-btn btn-pay" data-owner="<?php echo e($store->store_id); ?>">
                                                Thanh toán
                                            </button>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            
                            <tr>
                                <td colspan="4" style="font-weight:bold; text-align:right;">Tổng tiền:</td>
                                <td colspan="2" style="font-weight:bold;" id="shopTotal_<?php echo e($storeId); ?>">
                                    <?php echo e(number_format($totalStorePrice)); ?>đ
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Giỏ hàng của bạn trống.</p>
                <?php endif; ?>
            </div>
        </div>

        
        <?php $__currentLoopData = $buys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <input type="hidden" name="products[<?php echo e($item['product_id']); ?>][name]" value="<?php echo e($item['name']); ?>">
            <input type="hidden" name="products[<?php echo e($item['product_id']); ?>][price]" value="<?php echo e($item['price']); ?>">
            <input type="hidden" name="products[<?php echo e($item['product_id']); ?>][quantity]" value="<?php echo e($item['quantity']); ?>">
            <input type="hidden" name="products[<?php echo e($item['product_id']); ?>][product_size_id]" value="<?php echo e($item['product_size_id'] ?? ''); ?>">
            <input type="hidden" name="products[<?php echo e($item['product_id']); ?>][store_id]" value="<?php echo e($item['store_id'] ?? 0); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        
        <?php if(count($buys) > 0): ?>
        <div class="footer-link" style="margin-top:50px;" id="offlineSubmitBtn">
            <button type="submit" class="order-football-btn">Xác nhận mua hàng</button>
        </div>
        <?php endif; ?>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="payment_method"]');
    const onlineCells = document.querySelectorAll('.online-only');
    const cartWrapper = document.querySelector('.cart-pay-wrapper');
    const offlineSubmitBtn = document.getElementById('offlineSubmitBtn');
    const paymentInfo = document.getElementById('paymentInfo');

    function updateTableColumn(value) {
        const isOnline = value === 'online';

        // Hiện / ẩn cột tùy chọn
        onlineCells.forEach(cell => {
            cell.style.display = isOnline ? 'table-cell' : 'none';
        });

        // Hiện / ẩn nút offline
        if (offlineSubmitBtn) {
            offlineSubmitBtn.style.display = isOnline ? 'none' : 'block';
        }

        // Thêm / xóa class online-mode cho wrapper
        if (cartWrapper) {
            if (isOnline) {
                cartWrapper.classList.add('online-mode');
            } else {
                cartWrapper.classList.remove('online-mode');
            }
        }
    }

    // Chạy khi load trang
    updateTableColumn(document.querySelector('input[name="payment_method"]:checked').value);

    // Lắng nghe thay đổi radio
    radios.forEach(r => {
        r.addEventListener('change', function() {
            updateTableColumn(this.value);
        });
    });

    // Nút thanh toán online
    document.querySelectorAll('.btn-pay').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const ownerId = this.dataset.owner;
            if (ownerId) {
                window.location.href = `/thanh-toan-gio-hang/online/${ownerId}`;
            } else {
                alert('Không tìm thấy chủ cửa hàng !');
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/cartpay.blade.php ENDPATH**/ ?>