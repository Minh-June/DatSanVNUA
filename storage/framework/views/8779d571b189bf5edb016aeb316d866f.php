

<?php $__env->startSection('title', 'Quáº£n lĂ½ Ä‘Æ¡n Ä‘áº·t sĂ¢n thá»ƒ thao'); ?>

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

    <h3>Danh sĂ¡ch Ä‘Æ¡n sĂ¢n thá»ƒ thao</h3>

    <div class="admin-top-bar">
        <div class="admin-search">
            <form method="GET" action="<?php echo e(route('quan-ly-don-dat-san')); ?>">
                <label for="selected_date">Chá»n ngĂ y:</label>
                <input type="date" id="selected_date" name="selected_date" value="<?php echo e(request('selected_date')); ?>">
                <button class="admin-search-btn" type="submit">TĂ¬m kiáº¿m</button>
            </form>
        </div>

        <div class="admin-add-btn">
            <a href="<?php echo e(route('them-don-dat-san')); ?>">ThĂªm Ä‘Æ¡n Ä‘áº·t sĂ¢n</a>
        </div>
    </div>

    <?php if($orders->count() > 0): ?>
        <table id="ListCustomers">
            <tr>
                <th>STT</th>
                <th>NgĂ y táº¡o</th>
                <th>Há» vĂ  tĂªn</th>
                <th>SÄT</th>
                <!-- <th>TĂªn sĂ¢n</th>
                <th>NgĂ y thuĂª</th>
                <th>Thá»i gian</th>
                <th>Ghi chĂº</th> -->
                <th>ThĂ nh tiá»n</th>
                <th>áº¢nh thanh toĂ¡n</th>
                <th>ThĂ´ng tin</th>
                <th colspan="2">TĂ¹y chá»n</th>
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

                        <!-- <td><?php echo e($detail['yard']->name ?? 'KhĂ´ng xĂ¡c Ä‘á»‹nh'); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($detail['date'])->format('d/m/Y')); ?></td>
                        <td><?php echo e($detail['times']); ?></td>
                        <td><?php echo e($detail['notes'] ?: 'KhĂ´ng cĂ³'); ?></td> -->

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
                                        <img src="<?php echo e(asset('storage/' . $img)); ?>" alt="áº¢nh" style="width:100px; height:200px; cursor:pointer;" onclick="showImage(this.src)">
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    KhĂ´ng cĂ³
                                <?php endif; ?>
                            </td>
                            
                            <td rowspan="<?php echo e($rowspan); ?>">
                                <a href="<?php echo e(route('cap-nhat-don-dat-san', $order->order_id)); ?>">Xem chi tiáº¿t</a>
                            </td>

                            <td rowspan="<?php echo e($rowspan); ?>">
                                <form method="POST" action="<?php echo e(route('cap-nhat-trang-thai-don-dat-san', $order->order_id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <select name="status">
                                        <option value="0" <?php echo e($order->status == 0 ? 'selected' : ''); ?>>Chá» xĂ¡c nháº­n</option>
                                        <option value="1" <?php echo e($order->status == 1 ? 'selected' : ''); ?>>XĂ¡c nháº­n</option>
                                        <option value="2" <?php echo e($order->status == 2 ? 'selected' : ''); ?>>Há»§y</option>
                                    </select><br>
                                    <button type="submit" class="update-btn">Cáº­p nháº­t</button>
                                </form>
                            </td>

                            <td rowspan="<?php echo e($rowspan); ?>">
                                <form method="POST" action="<?php echo e(route('xoa-don-dat-san', $order->order_id)); ?>" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c muá»‘n xĂ³a Ä‘Æ¡n nĂ y?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="update-btn">XĂ³a</button>
                                </form>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    <?php else: ?>
        <p>KhĂ´ng cĂ³ káº¿t quáº£</p>
    <?php endif; ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>
