

<?php $__env->startSection('title', 'Quản lý thống kê, báo cáo'); ?>

<?php $__env->startSection('content'); ?>
    <h2>Thống kê doanh thu</h2>

    <div class="admin-top-bar-statement">
        <div class="admin-top-bar">
                <div class="admin-search">
                    <form method="GET" action="<?php echo e(route('thong-ke-bao-cao')); ?>">
                        <label for="filter_type">Kiểu thống kê:</label>
                        <select name="filter_type" id="filter_type" onchange="toggleInputs()" required style="width: 102px; margin-bottom: 6px;">
                            <option value="date" <?php echo e(request('filter_type') == 'date' ? 'selected' : ''); ?>>Theo ngày</option>
                            <option value="month" <?php echo e(request('filter_type') == 'month' ? 'selected' : ''); ?>>Theo tháng</option>
                            <option value="year" <?php echo e(request('filter_type') == 'year' ? 'selected' : ''); ?>>Theo năm</option>
                        </select>

                        <button type="submit" class="update-btn" style="margin-left:5px;">Xem báo cáo</button>
                    
                        <div id="input-date" style="<?php echo e(request('filter_type') != 'date' ? 'display:none;' : ''); ?>; margin-top:5px;">
                            <label for="date">Ngày:</label>
                            <input type="date" style="width: 169px;" name="date" id="date" value="<?php echo e(request('date', date('Y-m-d'))); ?>">
                        </div>

                        <div id="input-month" style="<?php echo e(request('filter_type') != 'month' ? 'display:none;' : ''); ?>; margin-top:5px;">
                            <label for="month">Tháng:</label>
                            <input type="month" style="width: 164px;" name="month" id="month" value="<?php echo e(request('month', date('Y-m'))); ?>">
                        </div>
                        
                        <div id="input-year" style="<?php echo e(request('filter_type') != 'year' ? 'display:none;' : ''); ?>; margin-top:5px;">
                            <label for="year">Năm:</label>
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
                    <button type="submit" class="delete-btn">
                        <i class="fa-solid fa-file-export"></i>
                        Xuất Excel
                    </button>
                </form>
            </div>
        </div>
    </div>

    <?php if(isset($totalRevenue)): ?>
        <h2 style="color:var(--primary-color);">Tổng doanh thu: <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?>đ</h2>

        <?php if($groupFixed->isNotEmpty()): ?>
            <h2 style="text-align: left; margin-left: 40px;">Doanh thu thuê sân cố định</h2>
            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Loại sân</th>
                        <th>Tên sân</th>
                        <th>Số đơn đặt</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sttFixed = 1; ?>

                    <?php $__currentLoopData = $groupFixed; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeName => $yards): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $rowCount = $yards->count(); $firstTypeRow = true; ?>
                        <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yardName => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <?php if($firstTypeRow): ?>
                                    <td rowspan="<?php echo e($rowCount); ?>"><?php echo e($sttFixed++); ?></td>
                                    <td rowspan="<?php echo e($rowCount); ?>" class="left-align"><?php echo e($typeName ?? 'Loại sân không xác định'); ?></td>
                                    <?php $firstTypeRow = false; ?>
                                <?php endif; ?>
                                <td class="left-align"><?php echo e($yardName ?? 'Sân không tồn tại'); ?></td>
                                <td>
                                    <a>
                                        <?php echo e($data['booking_count']); ?>

                                    </a>
                                </td>
                                <td><?php echo e(number_format($data['total_revenue'], 0, ',', '.')); ?>đ</td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Tổng doanh thu:</strong></td> 
                        <td><?php echo e(number_format($groupFixed->sum(fn($yards) => $yards->sum(fn($data) => $data['total_revenue'])), 0, ',', '.')); ?>đ</td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>

        
        <?php if($groupByTypeThenYard->isNotEmpty()): ?>
            <h2 style="text-align: left; margin-left: 40px;">Doanh thu thuê sân lẻ</h2>
            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Loại sân</th>
                        <th>Tên sân</th>
                        <th>Số đơn đặt</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                <?php $stt = 1; ?>
                <?php $__currentLoopData = $groupByTypeThenYard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeName => $yards): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $rowCount = $yards->count();
                        $firstTypeRow = true;
                    ?>
                    <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yardName => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <?php if($firstTypeRow): ?>
                                <td rowspan="<?php echo e($rowCount); ?>"><?php echo e($stt++); ?></td>
                                <td class="left-align" rowspan="<?php echo e($rowCount); ?>"><?php echo e($typeName ?? 'Loại sân không tồn tại'); ?></td>
                                <?php $firstTypeRow = false; ?>
                            <?php endif; ?>
                            <td class="left-align"><?php echo e($yardName ?? 'Sân không tồn tại'); ?></td>
                            <td>
                                <a>
                                    <?php echo e($data['booking_count']); ?>

                                </a>
                            </td>
                            <td><?php echo e(number_format($data['total_revenue'], 0, ',', '.')); ?>đ</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Tổng doanh thu:</strong></td> 
                        <td><?php echo e(number_format($groupByTypeThenYard->sum(fn($yards) => $yards->sum(fn($data) => $data['total_revenue'])), 0, ',', '.')); ?>đ</td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>

        
        <?php $user = auth()->user(); ?>
        <?php if($user->role != 0 && $groupProduct->isNotEmpty()): ?>
            <h2 style="text-align: left; margin-left: 40px;">Doanh thu bán hàng</h2>
            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Loại sản phẩm</th>
                        <th>Sản phẩm</th>
                        <th>Số đơn đặt</th>
                        <th>Doanh thu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $stt = 1; ?>
                    <?php $__currentLoopData = $groupProduct; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($stt++); ?></td>
                            <td class="left-align"><?php echo e($data['type_name']); ?></td>
                            <td class="left-align"><?php echo e($data['product_name']); ?></td>
                            <td>
                                <a>
                                    <?php echo e($data['total_orders']); ?>

                                </a>
                            </td>
                            <td><?php echo e(number_format($data['total_revenue'], 0, ',', '.')); ?>đ</td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td colspan="4" style="text-align: right;"><strong>Tổng doanh thu:</strong></td> 
                        <td><?php echo e(number_format($groupProduct->sum('total_revenue'), 0, ',', '.')); ?>đ</td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>

    <script>
        function toggleInputs() {
            const filterType = document.getElementById('filter_type').value;
            document.getElementById('input-date').style.display = filterType === 'date' ? 'inline-block' : 'none';
            document.getElementById('input-month').style.display = filterType === 'month' ? 'inline-block' : 'none';
            document.getElementById('input-year').style.display = filterType === 'year' ? 'inline-block' : 'none';
        }

        // Gọi khi trang load
        document.addEventListener('DOMContentLoaded', toggleInputs);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/statements/index.blade.php ENDPATH**/ ?>