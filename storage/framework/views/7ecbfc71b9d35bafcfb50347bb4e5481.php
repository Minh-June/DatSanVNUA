

<?php $__env->startSection('title', 'Cập nhật chi tiết đơn đặt mua hàng'); ?>

<?php $__env->startSection('content'); ?>
<?php if(session('price_change_message')): ?>
    <script>alert("<?php echo e(session('price_change_message')); ?>");</script>
<?php endif; ?>
<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<h2>Chi tiết đơn mua hàng</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <a class="update-btn" href="<?php echo e(route('quan-ly-don-mua-hang')); ?>">
            <i class="fa-solid fa-arrow-left"></i> Quay lại
        </a>
    </div>
    <div class="admin-add-btn">
    </div>
</div>

<?php $currentUser = auth()->user(); ?>

<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Sản phẩm</th>
            <th>Size</th>
            <th>Đơn giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            
            <?php if($currentUser->role != 3): ?>
                <th colspan="2">Tùy chọn</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $order->orderDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($loop->iteration); ?></td>
        <td><?php echo e($detail->product->name ?? 'Không xác định'); ?></td>
        <td><?php echo e($detail->size->name ?? 'Không có'); ?></td>

        
        <td><?php echo e(number_format($detail->price, 0, ',', '.')); ?>đ</td>

        
        <td><?php echo e($detail->quantity); ?></td>

        
        <td><?php echo e(number_format($detail->price * $detail->quantity, 0, ',', '.')); ?>đ</td>

        <?php if($currentUser->role != 3): ?>
            <td>
                <form action="<?php echo e(route('cap-nhat-chi-tiet-don-mua-hang', $detail->product_order_detail_id)); ?>" method="GET">
                    <button type="submit" class="update-btn">Sửa</button>
                </form>
            </td>
            <td>
                <form method="POST" action="<?php echo e(route('xoa-chi-tiet-don-mua-hang', $detail->product_order_detail_id)); ?>" onsubmit="return confirm('Bạn có chắc muốn xóa chi tiết đơn này?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="delete-btn">Xóa</button>
                </form>
            </td>
        <?php endif; ?>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align:right;"><strong>Tổng tiền:</strong></td>
            <td colspan="<?php echo e($currentUser->role != 3 ? 3 : 1); ?>">
                <strong>
                    <?php echo e(number_format($order->orderDetails->sum(function($d){ return $d->price * $d->quantity; }), 0, ',', '.')); ?>đ
                </strong>
            </td>
        </tr>
    </tfoot>
</table>

<?php if(isset($editDetail)): ?>
<h2 style="margin-top:30px;">Cập nhật thông tin chi tiết đơn</h2>

<div class="adminedit">
    <form method="POST" action="<?php echo e(route('update-chi-tiet-don-mua-hang', $editDetail->product_order_detail_id)); ?>">
        <?php echo csrf_field(); ?>
        
        <div class="adminedit-form-group">
            <label>Sản phẩm:</label>
            <select name="product_id" id="productSelect" required>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($p->product_id); ?>"
                        <?php echo e($editDetail->product_id == $p->product_id ? 'selected' : ''); ?>>
                        <?php echo e($p->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        
        <div class="adminedit-form-group">
            <label>Size:</label>
            <select name="product_size_id" id="sizeSelect"
                <?php echo e($editDetail->product->sizes->count() ? '' : 'disabled'); ?>>

                <?php $__currentLoopData = $editDetail->product->sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s->product_size_id); ?>"
                        data-price="<?php echo e($s->price); ?>"
                        <?php echo e($editDetail->product_size_id == $s->product_size_id ? 'selected' : ''); ?>>
                        <?php echo e($s->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        
        <div class="adminedit-form-group">
            <label>Đơn giá:</label>
            <input type="text" id="priceInput"
                value="<?php echo e(number_format($editDetail->price,0,',','.')); ?>đ"
                disabled>
        </div>
        
        <div class="adminedit-form-group">
            <label>Số lượng:</label>
            <input type="number" id="quantityInput" name="quantity"
                value="<?php echo e($editDetail->quantity); ?>" min="1" required>
        </div>
        
        <div class="adminedit-form-group">
            <label>Thành tiền:</label>
            <input type="text" id="totalInput"
                value="<?php echo e(number_format($editDetail->price * $editDetail->quantity,0,',','.')); ?>đ"
                disabled>
        </div>
        <div class="adminedit-button">
            <button type="submit" class="update-btn">Cập nhật thông tin</button>
        </div>
    </form>
</div>
<?php endif; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Đổi sản phẩm → load size + giá
$('#productSelect').on('change', function () {
    let productId = $(this).val();

    $.ajax({
        url: '/admin/ajax/product-info/' + productId,
        type: 'GET',
        success: function (data) {

            $('#sizeSelect').empty();

            // Có size
            if (data.sizes.length > 0) {
                $('#sizeSelect').prop('disabled', false);

                data.sizes.forEach(s => {
                    $('#sizeSelect').append(`
                        <option value="${s.product_size_id}" data-price="${s.price}">
                            ${s.name} (${parseInt(s.price).toLocaleString('vi-VN')}đ)
                        </option>
                    `);
                });

                let price = data.sizes[0].price;
                $('#priceInput').val(price.toLocaleString('vi-VN') + 'đ');

            } else {
                // Không có size
                $('#sizeSelect').prop('disabled', true);

                let price = data.product.price;
                $('#priceInput').val(price.toLocaleString('vi-VN') + 'đ');
            }

            updateTotal();
        }
    });
});

// Đổi size
$('#sizeSelect').on('change', function () {
    let price = $(this).find(':selected').data('price') || 0;
    $('#priceInput').val(price.toLocaleString('vi-VN') + 'đ');
    updateTotal();
});

// Đổi số lượng
$('#quantityInput').on('input', function () {
    updateTotal();
});

// Tính thành tiền
function updateTotal() {
    let price = parseInt($('#priceInput').val().replace(/\D/g, '')) || 0;
    let qty = parseInt($('#quantityInput').val()) || 1;
    let total = price * qty;

    $('#totalInput').val(total.toLocaleString('vi-VN') + 'đ');
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/productorders/update.blade.php ENDPATH**/ ?>