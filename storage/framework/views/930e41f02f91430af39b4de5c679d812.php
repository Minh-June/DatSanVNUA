

<?php $__env->startSection('title', 'Quản lý đơn đặt sân thể thao'); ?>

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

    <h3>Danh sách đơn sân thể thao</h3>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="<?php echo e(route('quan-ly-don-dat-san')); ?>">
                <label for="selected_date">Chọn ngày:</label>
                <input type="date" id="selected_date" name="selected_date" value="<?php echo e(request('selected_date')); ?>">
                <button class="admin-search-btn" type="submit">Tìm kiếm</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a href="<?php echo e(route('them-don-dat-san')); ?>">Thêm đơn đặt sân</a>
        </div>
    </div>

    <?php if($orders->count() > 0): ?>
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>Ngày tạo</th>
                <th>Họ và tên</th>
                <th>SĐT</th>
                <!-- <th>Tên sân</th>
                <th>Ngày thuê</th>
                <th>Thời gian</th>
                <th>Ghi chú</th> -->
                <th>Thành tiền</th>
                <th>Ảnh thanh toán</th>
                <th>Thông tin</th>
                <th colspan="2">Tùy chọn</th>
            </tr>

            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $rowspan = $order->groupedDetails->count();
                ?>

                <?php $__currentLoopData = $order->groupedDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <?php if($index === 0): ?>
                            <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($key + 1); ?></td>
                            <td rowspan="<?php echo e($rowspan); ?>">                
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?><br>
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('H:i')); ?>

                            </td>
                            <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($order->name); ?></td>
                            <td rowspan="<?php echo e($rowspan); ?>"><?php echo e($order->phone); ?></td>
                        <?php endif; ?>

                        <!-- <td><?php echo e($detail['yard']->name ?? 'Không xác định'); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($detail['date'])->format('d/m/Y')); ?></td>
                        <td><?php echo e($detail['times']); ?></td>
                        <td><?php echo e($detail['notes'] ?: 'Không có'); ?></td> -->

                        <?php if($index === 0): ?>
                            <td rowspan="<?php echo e($rowspan); ?>">
                                <?php echo e(number_format($order->orderDetails->sum('price'), 0, ',', '.')); ?> VND
                            </td>
                        <?php endif; ?>

                        <?php if($index === 0): ?>
                            <td rowspan="<?php echo e($rowspan); ?>">
                                <?php $images = json_decode($order->image); ?>
                                <?php if($images && count($images) > 0): ?>
                                    <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="Ảnh" style="width:100px; height:200px; cursor:pointer;" onclick="showImage(this.src)">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    Không có
                                <?php endif; ?>
                            </td>
                            
                            <td rowspan="<?php echo e($rowspan); ?>">
                                <a href="<?php echo e(route('cap-nhat-don-dat-san', $order->order_id)); ?>">Xem chi tiết</a>
                            </td>

                            <td rowspan="<?php echo e($rowspan); ?>">
                                <form method="POST" action="<?php echo e(route('cap-nhat-trang-thai-don-dat-san', $order->order_id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <select name="status">
                                        <option value="0" <?php echo e($order->status == 0 ? 'selected' : ''); ?>>Chờ xác nhận</option>
                                        <option value="1" <?php echo e($order->status == 1 ? 'selected' : ''); ?>>Xác nhận</option>
                                        <option value="2" <?php echo e($order->status == 2 ? 'selected' : ''); ?>>Hủy</option>
                                    </select><br>
                                    <button type="submit" class="update-btn">Cập nhật</button>
                                </form>
                            </td>

                            <td rowspan="<?php echo e($rowspan); ?>">
                                <form method="POST" action="<?php echo e(route('xoa-don-dat-san', $order->order_id)); ?>" onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="update-btn">Xóa</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    <?php else: ?>
        <p>Không có kết quả</p>
    <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>