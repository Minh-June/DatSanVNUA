

<?php $__env->startSection('title', 'Lá»‹ch sá»­ Ä‘áº·t sĂ¢n'); ?>

<?php $__env->startSection('content'); ?> 
    <h3>Danh sĂ¡ch sĂ¢n Ä‘Ă£ Ä‘áº·t</h3>  
    
    <!-- Begin: Date Filter -->
    <div class="admin-time">
        <form method="GET" action="<?php echo e(route('thong-tin-tai-khoan')); ?>">
            <label for="selected_date">Chá»n ngĂ y:</label>
            <input type="date" id="selected_date" name="selected_date" value="<?php echo e(request('selected_date')); ?>" required>
            <button class="admin-time-btn" type="submit" name="filter_date">TĂ¬m kiáº¿m</button>
        </form>
    </div>        
    <!-- End: Date Filter -->

    <!-- Begin: Display Orders -->
    <?php if($orders->count() > 0): ?>
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>Há» vĂ  tĂªn</th>
                <th>Sá»‘ Ä‘iá»‡n thoáº¡i</th>
                <th>TĂªn sĂ¢n</th>
                <th>Sá»‘ sĂ¢n</th>
                <th>NgĂ y</th>
                <th>Thá»i gian</th>
                <th>ThĂ nh tiá»n</th>
                <th>Ghi chĂº</th>
                <th>Tráº¡ng thĂ¡i</th>
            </tr>
            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($key + 1); ?></td>
                    <td><?php echo e($order->name); ?></td>
                    <td><?php echo e($order->phone); ?></td>
                    <td><?php echo e($order->san ? $order->san->tensan : 'KhĂ´ng xĂ¡c Ä‘á»‹nh'); ?></td>
                    <td><?php echo e($order->san ? $order->san->sosan : 'KhĂ´ng xĂ¡c Ä‘á»‹nh'); ?></td>
                    <td><?php echo e($order->date); ?></td>
                    <td>
                        <?php $__currentLoopData = explode(',', $order->time); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeSlot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($timeSlot); ?> <br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td><?php echo e($order->price); ?> VND</td>
                    <td><?php echo e($order->notes); ?></td>
                    <td>
                        <?php switch($order->status):
                            case ('choxacnhan'): ?>
                                Chá» xĂ¡c nháº­n
                                <?php break; ?>
                            <?php case ('xacnhan'): ?>
                                ÄĂ£ xĂ¡c nháº­n
                                <?php break; ?>
                            <?php case ('huydon'): ?>
                                ÄÆ¡n Ä‘Ă£ bá»‹ há»§y
                                <?php break; ?>
                            <?php default: ?>
                                KhĂ´ng xĂ¡c Ä‘á»‹nh
                        <?php endswitch; ?>
                    </td>                                        
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    <?php else: ?>
        <p>KhĂ´ng cĂ³ káº¿t quáº£</p>
    <?php endif; ?>
    <!-- End: Display Orders -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/user/index.blade.php ENDPATH**/ ?>
