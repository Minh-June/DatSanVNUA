

<?php $__env->startSection('title', 'Quáº£n lĂ½ thá»‘ng kĂª, bĂ¡o cĂ¡o'); ?>

<?php $__env->startSection('content'); ?>
    <h3>Thá»‘ng kĂª doanh thu</h3>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="<?php echo e(route('thong-ke-bao-cao')); ?>">
                <label for="filter_type">Chá»n kiá»ƒu thá»‘ng kĂª:</label>
                <select name="filter_type" id="filter_type" onchange="toggleInputs()" required style="width: 100px;">
                    <option value="date" <?php echo e(request('filter_type') == 'date' ? 'selected' : ''); ?>>Theo ngĂ y</option>
                    <option value="month" <?php echo e(request('filter_type') == 'month' ? 'selected' : ''); ?>>Theo thĂ¡ng</option>
                    <option value="year" <?php echo e(request('filter_type') == 'year' ? 'selected' : ''); ?>>Theo nÄƒm</option>
                </select>
                <button type="submit" class="admin-search-btn">Xem bĂ¡o cĂ¡o</button>
            
                <div id="input-date" style="<?php echo e(request('filter_type') != 'date' ? 'display:none;' : ''); ?>">
                    <label for="date">Chá»n ngĂ y:</label>
                    <input type="date" style="width: 165px;" name="date" id="date" value="<?php echo e(request('date', date('Y-m-d'))); ?>">
                </div>

                <div id="input-month" style="<?php echo e(request('filter_type') != 'month' ? 'display:none;' : ''); ?>">
                    <label for="month">Chá»n thĂ¡ng:</label>
                    <input type="month" style="width: 160px;" name="month" id="month" value="<?php echo e(request('month', date('Y-m'))); ?>">
                </div>
                
                <div id="input-year" style="<?php echo e(request('filter_type') != 'year' ? 'display:none;' : ''); ?>">
                    <label for="year">Chá»n nÄƒm:</label>
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
                <button type="submit" class="update-btn">Xuáº¥t file excel</button>
            </form>
        </div>
    </div>

    <?php if(isset($totalRevenue)): ?>
        <h3>Tá»•ng doanh thu: <?php echo e(number_format($totalRevenue, 0, ',', '.')); ?> VNÄ</h3>

        <?php if($totalRevenue > 0): ?>
            <h3>Doanh thu tá»«ng sĂ¢n</h3>

            <div class="admin-top-bar">
                <div class="admin-search">
                    <form method="GET" action="<?php echo e(route('thong-ke-bao-cao')); ?>">
                        <input type="hidden" name="filter_type" value="<?php echo e(request('filter_type')); ?>">
                        <input type="hidden" name="date" value="<?php echo e(request('date')); ?>">
                        <input type="hidden" name="month" value="<?php echo e(request('month')); ?>">
                        <input type="hidden" name="year" value="<?php echo e(request('year')); ?>">

                        <label for="keyword">TĂ¬m sĂ¢n:</label>
                        <input type="text" id="keyword" name="keyword" placeholder="Nháº­p tĂªn sĂ¢n cáº§n tĂ¬m" value="<?php echo e(request('keyword')); ?>">
                        <button class="admin-search-btn" type="submit">TĂ¬m kiáº¿m</button>
                    </form>
                </div>
            </div>

            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>TĂªn sĂ¢n</th>
                        <th>Doanh thu (VNÄ)</th>
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
            <h3 style="font-weight: normal; font-size: 17px;">Hiá»‡n táº¡i chÆ°a cĂ³ dá»¯ liá»‡u bĂ¡o cĂ¡o.</h3>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/admin/statements/index.blade.php ENDPATH**/ ?>
