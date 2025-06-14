

<?php $__env->startSection('title', 'Quản lý thống kê, báo cáo'); ?>

<?php $__env->startSection('content'); ?>
    <h3>Thống kê doanh thu</h3>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="<?php echo e(route('thong-ke-bao-cao')); ?>">
                <label for="filter_type">Chọn kiểu thống kê:</label>
                <select name="filter_type" id="filter_type" onchange="toggleInputs()" required style="width: 100px;">
                    <option value="date" <?php echo e(request('filter_type') == 'date' ? 'selected' : ''); ?>>Theo ngày</option>
                    <option value="month" <?php echo e(request('filter_type') == 'month' ? 'selected' : ''); ?>>Theo tháng</option>
                    <option value="year" <?php echo e(request('filter_type') == 'year' ? 'selected' : ''); ?>>Theo năm</option>
                </select>
                <button type="submit" class="admin-search-btn">Xem báo cáo</button>
            
                <div id="input-date" style="<?php echo e(request('filter_type') != 'date' ? 'display:none;' : ''); ?>">
                    <label for="date">Chọn ngày:</label>
                    <input type="date" style="width: 165px;" name="date" id="date" value="<?php echo e(request('date', date('Y-m-d'))); ?>">
                </div>

                <div id="input-month" style="<?php echo e(request('filter_type') != 'month' ? 'display:none;' : ''); ?>">
                    <label for="month">Chọn tháng:</label>
                    <input type="month" style="width: 160px;" name="month" id="month" value="<?php echo e(request('month', date('Y-m'))); ?>">
                </div>
                
                <div id="input-year" style="<?php echo e(request('filter_type') != 'year' ? 'display:none;' : ''); ?>">
                    <label for="year">Chọn năm:</label>
                    <input type="number" name="year" id="year" min="2000" max="<?php echo e(date('Y')); ?>" value="<?php echo e(request('year', date('Y'))); ?>">
                </div>
            </form>
        </div>
        
        <div class="admin-add-btn">
            <form method="GET" action="<?php echo e(route('xuat-excel-doanh-thu')); ?>">
                <input type="hidden" name="filter_type" value="<?php echo e(request('filter_type')); ?>">
                <input type="hidden" name="date" value="<?php echo e(request('date')); ?>">
                <input type="hidden" name="month" value="<?php echo e(request('month')); ?>">
                <input type="hidden" name="year" value="<?php echo e(request('year')); ?>">
                <button type="submit" class="update-btn">Xuất file excel</button>
            </form>
        </div>
    </div>

    <?php if(isset($totalRevenue)): ?>
        <h3>Tổng doanh thu: <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?> VNĐ</h3>

        <?php if($totalRevenue > 0): ?>
            <h3>Doanh thu từng sân</h3>

            <div class="admin-top-bar">
                <div class="admin-search">
                    <form method="GET" action="<?php echo e(route('thong-ke-bao-cao')); ?>">
                        <input type="hidden" name="filter_type" value="<?php echo e(request('filter_type')); ?>">
                        <input type="hidden" name="date" value="<?php echo e(request('date')); ?>">
                        <input type="hidden" name="month" value="<?php echo e(request('month')); ?>">
                        <input type="hidden" name="year" value="<?php echo e(request('year')); ?>">

                        <label for="keyword">Tìm sân:</label>
                        <input type="text" id="keyword" name="keyword" placeholder="Nhập tên sân cần tìm" value="<?php echo e(request('keyword')); ?>">
                        <button class="admin-search-btn" type="submit">Tìm kiếm</button>
                    </form>
                </div>
            </div>

            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên sân</th>
                        <th>Doanh thu (VNĐ)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $byYard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yardName => $revenue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td><?php echo e($yardName); ?></td>
                            <td><?php echo e(number_format($revenue, 0, ',', '.')); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <h3 style="font-weight: normal; font-size: 17px;">Hiện tại chưa có dữ liệu báo cáo.</h3>
        <?php endif; ?>
    <?php endif; ?>

    <script>
        function toggleInputs() {
            const filterType = document.getElementById('filter_type').value;
            document.getElementById('input-date').style.display = filterType === 'date' ? 'inline-block' : 'none';
            document.getElementById('input-month').style.display = filterType === 'month' ? 'inline-block' : 'none';
            document.getElementById('input-year').style.display = filterType === 'year' ? 'inline-block' : 'none';
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/statements/index.blade.php ENDPATH**/ ?>