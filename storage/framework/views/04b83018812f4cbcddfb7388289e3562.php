

<?php $__env->startSection('title', 'Lá»‹ch sá»­ Ä‘áº·t sĂ¢n'); ?>

<?php $__env->startSection('content'); ?> 
    <h3>Danh sĂ¡ch Ä‘Æ¡n Ä‘áº·t sĂ¢n</h3>  
    
    <!-- Begin: Date Filter -->
    <div class="admin-search">
        <form method="GET" action="<?php echo e(route('thong-tin-tai-khoan')); ?>">
            <label for="date">Chá»n ngĂ y:</label>
            <input type="date" id="date" name="date" value="<?php echo e(request('date', date('Y-m-d'))); ?>">
            <button class="update-btn" type="submit">TĂ¬m kiáº¿m</button>
        </form>
    </div>        
    <!-- End: Date Filter -->

    <!-- Begin: Display Orders -->
    <?php if($orders->count() > 0): ?>
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>NgĂ y Ä‘áº·t</th>
                <!-- <th>Há» vĂ  tĂªn</th>
                <th>Sá»‘ Ä‘iá»‡n thoáº¡i</th> -->
                <th>NgĂ y thuĂª</th>
                <th>TĂªn sĂ¢n</th>
                <th>Thá»i gian</th>
                <!-- <th>GiĂ¡ tá»«ng khung giá»</th> -->
                <th>ThĂ nh tiá»n</th>
                <th>Ghi chĂº</th>
                <th>áº¢nh thanh toĂ¡n</th>
                <th>Tráº¡ng thĂ¡i</th>
            </tr>
            <?php $index = 1; ?>
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $totalDetailsCount = $order->orderDetails->count();
                    $groupedDetails = $order->orderDetails->groupBy(function($item) {
                        return $item->yard_id . '_' . $item->date;
                    });
                    $orderRowspan = $totalDetailsCount;
                ?>

                <?php $__currentLoopData = $groupedDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $firstDetail = $group->first();
                        $timeString = $group->pluck('time')->implode('<br>');
                    ?>

                    <tr>
                        <?php if($loop->parent->first): ?>
                            <td rowspan="<?php echo e($orderRowspan); ?>"><?php echo e($index++); ?></td>
                            <td rowspan="<?php echo e($orderRowspan); ?>">
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('d/m/Y')); ?><br>
                                <?php echo e(\Carbon\Carbon::parse($order->date)->format('H:i')); ?>

                            </td>
                        <?php endif; ?>

                        <td><?php echo e(\Carbon\Carbon::parse($firstDetail->date)->format('d/m/Y')); ?></td>
                        <td><?php echo e($firstDetail->yard ? $firstDetail->yard->name : 'KhĂ´ng xĂ¡c Ä‘á»‹nh'); ?></td>
                        <td><?php echo $timeString; ?></td>

                        <?php if($loop->parent->first): ?>
                            <td rowspan="<?php echo e($orderRowspan); ?>">
                                <?php echo e(number_format($order->orderDetails->sum('price'), 0, ',', '.')); ?>Ä‘
                            </td>
                            <td rowspan="<?php echo e($orderRowspan); ?>"><?php echo e($firstDetail->notes ?? 'KhĂ´ng cĂ³'); ?></td>
                            <td rowspan="<?php echo e($orderRowspan); ?>">
                                <?php
                                    $images = json_decode($order->image) ?: [];
                                ?>
                                <?php if(count($images) > 0): ?>
                                    <?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="áº¢nh thanh toĂ¡n" style="width:100px; height:200px; cursor: pointer;" onclick="showImage('<?php echo e(asset('storage/' . $img)); ?>')">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    KhĂ´ng cĂ³ áº£nh
                                <?php endif; ?>
                            </td>
                            <td rowspan="<?php echo e($orderRowspan); ?>">
                                <?php switch($order->status):
                                    case (0): ?> Chá» xĂ¡c nháº­n <?php break; ?>
                                    <?php case (1): ?> ÄĂ£ xĂ¡c nháº­n <?php break; ?>
                                    <?php case (2): ?> ÄÆ¡n Ä‘Ă£ bá»‹ há»§y <?php break; ?>
                                    <?php default: ?> KhĂ´ng xĂ¡c Ä‘á»‹nh
                                <?php endswitch; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    <?php else: ?>
        <h3 style="font-weight: normal; font-size: 18px;">Hiá»‡n táº¡i báº¡n chÆ°a cĂ³ Ä‘Æ¡n Ä‘áº·t sĂ¢n nĂ o</h3>
    <?php endif; ?>
    <!-- End: Display Orders -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.account', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/account/index.blade.php ENDPATH**/ ?>