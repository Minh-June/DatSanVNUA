

<?php $__env->startSection('title','Quản lý đơn thuê cố định theo tháng'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>
<?php if(session('error')): ?>
    <script>alert("<?php echo e(session('error')); ?>");</script>
<?php endif; ?>

<?php
    $currentUser = auth()->user();
    $today = now()->toDateString();
    $statusOptions = [
        0 => 'Chờ xác nhận',
        1 => 'Xác nhận',
        2 => 'Hủy',
        3 => 'Đã đặt cọc'
    ];
?>

<h2>Danh sách đơn cố định</h2>

<div class="admin-top-bar">
    <div class="admin-search">
        <form method="GET" action="<?php echo e(route('quan-ly-don-dat-san-co-dinh')); ?>">
            <label for="selected_date">Ngày:</label>
            <input type="date" id="selected_date" name="selected_date"
                   value="<?php echo e(request('selected_date', now()->toDateString())); ?>">
            <button class="update-btn" type="submit">Tìm kiếm</button>
        </form>
    </div>

    <div class="admin-add-btn">
        <?php if(auth()->user()->role != 3): ?>
            <a class="update-btn" style="margin-left:10px;" href="<?php echo e(route('trang-chu')); ?>">Thêm đơn đặt sân cố định</a>
        <?php endif; ?>
    </div>
</div>

<?php if($orders->count()): ?>
<table id="ListCustomers">
    <thead>
        <tr>
            <th>STT</th>
            <th>Ngày đặt</th>
            <th>Họ và tên</th>
            <th>SĐT</th>
            <th>Tổng tiền</th>
            <th>Thanh toán</th>
            <th>Thông tin</th>
            <th colspan="2">Tùy chọn</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $orderDate = \Carbon\Carbon::parse($order->date)->toDateString();
                $endDate = \Carbon\Carbon::parse($order->to_date)->toDateString();

                $isOwner = $order->yard && $order->yard->user_id == $currentUser->user_id;
                $isStaff = $order->yard && $order->yard->user_id == $currentUser->manager_id;

                // Quyền chỉnh sửa: Admin, Chủ sân, Nhân viên nhưng chỉ với đơn chưa quá hạn
                $canEdit = ($currentUser->role == 0) ||
                           ($currentUser->role == 2 && $isOwner) ||
                           ($currentUser->role == 3 && $isStaff && $orderDate >= $today);
            ?>

            <tr>
                <td><?php echo e($loop->iteration); ?></td>

                
                <td>
                    <?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?><br>
                    <?php echo e(\Carbon\Carbon::parse($order->date)->format('H:i')); ?>

                </td>

                
                <td class="left-align"><?php echo e($order->user->fullname ?? 'Khách vãng lai'); ?></td>

                
                <td><?php echo e($order->user->phonenb ?? ''); ?></td>

                
                <td><?php echo e(number_format($order->price, 0, ',', '.')); ?>đ</td>

                
                <td>Thanh toán<br>tại sân</td>

                
                <td>
                    <a href="<?php echo e(route('cap-nhat-don-dat-san-co-dinh', $order->month_rent_id)); ?>">Chi tiết</a>
                </td>

                
                <?php if($canEdit): ?>
                    <td>
                        <form method="POST" action="<?php echo e(route('cap-nhat-trang-thai-don-dat-san-co-dinh', $order->month_rent_id)); ?>">
                            <?php echo csrf_field(); ?>
                            <select name="status">
                                <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($val); ?>" <?php echo e($order->status == $val ? 'selected' : ''); ?>>
                                        <?php echo e($text); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select><br>
                            <button class="update-btn" type="submit">Cập nhật</button>
                        </form>
                    </td>

                    <?php if($currentUser->role != 3): ?>
                        <td>
                            <form method="POST"
                                  action="<?php echo e(route('xoa-don-dat-san-co-dinh', $order->month_rent_id)); ?>"
                                  onsubmit="return confirm('Bạn có chắc muốn xóa đơn này?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="delete-btn" type="submit">Xóa</button>
                            </form>
                        </td>
                    <?php endif; ?>
                <?php else: ?>
                    <td colspan="2"> 
                        <?php switch($order->status):
                            case (0): ?>
                                <span class="status status-pending">Chờ xác nhận</span>
                                <?php break; ?>
                            <?php case (1): ?>
                                <span class="status status-confirmed">Đã xác nhận</span>
                                <?php break; ?>
                            <?php case (2): ?>
                                <span class="status status-cancelled">Đơn đã hủy</span>
                                <?php break; ?>
                            <?php case (3): ?>
                                <span class="status status-deposit">Đã đặt cọc</span>
                                <?php break; ?>
                            <?php default: ?>
                                <span class="status status-unknown">Không xác định</span>
                        <?php endswitch; ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php else: ?>
<p style="text-align:center; margin-top:20px;">
    Hiện tại chưa có đơn thuê cố định theo tháng nào
</p>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/fixedorder/index.blade.php ENDPATH**/ ?>