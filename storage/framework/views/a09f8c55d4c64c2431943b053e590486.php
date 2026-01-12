 

<?php $__env->startSection('title', 'Thông tin đơn vị thầu'); ?>

<?php $__env->startSection('content'); ?>
<?php
    use Carbon\Carbon;
    $maxDate = Carbon::now()->subYears(13)->format('Y-m-d');   
    $minDate = Carbon::now()->subYears(100)->format('Y-m-d'); 
    $user = auth()->user();
?>

<?php if($errors->any()): ?>
    <script>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            alert("<?php echo e($error); ?>");
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </script>
<?php endif; ?>

<?php if(session('success')): ?>
    <script>alert("<?php echo e(session('success')); ?>");</script>
<?php endif; ?>

<h2>Thông tin đơn vị thầu</h2>

<div class="adminedit-contractor">
    <div class="adminedit-contractor-left">
        <h2 style="margin:-5px 0 15px 0;">Chủ thầu</h2>
        <div class="adminedit">
            <form method="post" action="<?php echo e(route('cap-nhat-thong-tin-don-vi-thau')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="yard_id" value="<?php echo e($yardId ?? ''); ?>">

                <div class="adminedit-form-group">
                    <label>Họ và tên:</label>
                    <input type="text" value="<?php echo e($contractor->fullname ?? ''); ?>" disabled>
                </div>

                <div class="adminedit-form-group">
                    <label>Giới tính:</label>
                    <input type="text" value="<?php echo e($contractor->gender ?? ''); ?>" disabled>
                </div>

                <div class="adminedit-form-group">
                    <label>Ngày sinh:</label>
                    <input type="date"
                        value="<?php echo e($contractor->birthdate ?? ''); ?>"
                        min="<?php echo e($minDate); ?>"
                        max="<?php echo e($maxDate); ?>"
                        disabled>
                </div>

                <?php if($user->role == 0): ?>
                    <div class="adminedit-form-group">
                        <label>Số điện thoại:</label>
                        <div class="adminedit-select-contractor">
                            <select id="user_id" name="user_id" required>
                                <?php $__currentLoopData = $contractors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($c->user_id); ?>" <?php echo e($c->user_id == ($contractor->user_id ?? '') ? 'selected' : ''); ?>>
                                        <?php echo e($c->phonenb); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="adminedit-form-group">
                        <label>Số điện thoại:</label>
                        <input type="text" value="<?php echo e($contractor->phonenb ?? ''); ?>" disabled>
                    </div>
                <?php endif; ?>

                <div class="adminedit-form-group">
                    <label>Email:</label>
                    <input type="email" value="<?php echo e($contractor->email ?? ''); ?>" disabled>
                </div>

                <?php if(auth()->user()->role == 0): ?>
                    <div class="adminedit-button" style="margin:0;">
                        <button type="submit" class="update-btn">Cập nhật thông tin</button>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="adminedit-contractor-right">
        <h2 style="margin:-5px 0 15px 0;">Danh sách nhân viên</h2>

        <table id='ListCustomers'>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên nhân viên</th>
                    <th>Ngày sinh</th>
                    <th>Số điện thoại</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td><?php echo e($emp->fullname); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($emp->birthdate)->format('d/m/Y')); ?></td>
                        <td><?php echo e($emp->phonenb); ?></td>
                        <td><?php echo e($emp->email); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Đơn vị chưa có nhân viên nào</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="yard-statements"> 
    <h2>Thông số thống kê</h2>

    <div class="admin-top-bar-statement" style="margin-top:30px;">
        <div class="admin-top-bar">
            <div class="admin-search">
                <form method="GET" action="<?php echo e(route('thong-tin-don-vi-thau')); ?>">
                    <input type="hidden" name="user_id" value="<?php echo e($contractor->user_id); ?>">

                    <label for="filter_type">Kiểu thống kê:</label>
                    <select name="filter_type" id="filter_type" onchange="toggleInputs()" required style="width: 102px; margin-bottom: 6px;">
                        <option value="date" <?php echo e($filterType == 'date' ? 'selected' : ''); ?>>Theo ngày</option>
                        <option value="month" <?php echo e($filterType == 'month' ? 'selected' : ''); ?>>Theo tháng</option>
                        <option value="year" <?php echo e($filterType == 'year' ? 'selected' : ''); ?>>Theo năm</option>
                    </select>

                    <button type="submit" class="update-btn" style="margin-left:5px;">Xem báo cáo</button>

                    <div id="input-date" style="<?php echo e($filterType != 'date' ? 'display:none;' : ''); ?>; margin-top:5px;">
                        <label for="date">Ngày:</label>
                        <input type="date" name="date" id="date" value="<?php echo e(request('date', date('Y-m-d'))); ?>">
                    </div>

                    <div id="input-month" style="<?php echo e($filterType != 'month' ? 'display:none;' : ''); ?>; margin-top:5px;">
                        <label for="month">Tháng:</label>
                        <input type="month" name="month" id="month" value="<?php echo e(request('month', date('Y-m'))); ?>">
                    </div>

                    <div id="input-year" style="<?php echo e($filterType != 'year' ? 'display:none;' : ''); ?>; margin-top:5px;">
                        <label for="year">Năm:</label>
                        <input type="number" name="year" id="year" min="2000" max="<?php echo e(date('Y')); ?>" value="<?php echo e(request('year', date('Y'))); ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="adminedit-contractor-infor">
        <div class="adminedit-contractor-1">
            <h2>Đơn thuê cố định</h2>
            <p class="pick-order-1"><?php echo e($fixedOrderCount); ?></p>
        </div>

        <div class="adminedit-contractor-2">
            <h2>Đơn thuê lẻ</h2>
            <p class="pick-order-2"><?php echo e($orderCount); ?></p>
        </div>

        <div class="adminedit-contractor-3">
            <h2>Đơn mua hàng</h2>
            <p class="product-order"><?php echo e($purchaseCount); ?></p>
        </div>
    </div>
</div>

<script>
function toggleInputs() {
    const type = document.getElementById('filter_type').value;
    document.getElementById('input-date').style.display = type === 'date' ? 'block' : 'none';
    document.getElementById('input-month').style.display = type === 'month' ? 'block' : 'none';
    document.getElementById('input-year').style.display = type === 'year' ? 'block' : 'none';
}

const userRole = "<?php echo e($user->role); ?>";
if (userRole == 0) {
    document.getElementById('user_id').addEventListener('change', function() {
        const userId = this.value;
        const yardId = "<?php echo e($yardId ?? ''); ?>";
        if(!userId) return;

        window.location.href = "<?php echo e(route('thong-tin-don-vi-thau')); ?>" 
            + "?yard_id=" + yardId 
            + "&user_id=" + userId;
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/contractor/index.blade.php ENDPATH**/ ?>