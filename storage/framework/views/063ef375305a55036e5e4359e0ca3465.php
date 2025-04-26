

<?php $__env->startSection('title', 'Đặt sân'); ?>

<?php $__env->startSection('content'); ?>
        <!-- Begin: Content -->
        <div id="content" class="order-section">
            <h2 class="order-heading"><?php echo e($tensan); ?> - <?php echo e($sosan); ?></h2>
            <div class="order-content">
                <div class="order">
                    <div class="order-section-left">
                        <?php if($image): ?>
                            <img src="<?php echo e(asset(Storage::url($image->image))); ?>" alt="Sân <?php echo e($sosan); ?>" class="football-img">
                        <?php else: ?>
                            <img src="<?php echo e(asset('./image/football.jpg')); ?>" alt="Sân <?php echo e($tensan); ?> - <?php echo e($sosan); ?>" class="football-img">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="order">
                    <div class="order-section-right">
                        <div class="container">
                            <p>* Lưu ý: Khách hàng muốn đặt sân khác các khung giờ đang có sẵn, vui lòng liên hệ chủ sân theo SĐT: 0356645445 - 0563490783</p>
                            <form action="<?php echo e(route('xac-nhan-dat-san')); ?>" method="post" onsubmit="return validateForm()">
                                <?php echo csrf_field(); ?>
                                <div class="col col-half form-order-left">
                                    <div class="form-order-left-days">
                                        <label for="date">Chọn ngày:</label>
                                        <input type="date" id="date" name="date" value="<?php echo e(date('Y-m-d')); ?>" min="<?php echo e(date('Y-m-d')); ?>" onchange="fetchBookedTimes()">
                                    </div>
                            
                                    <label for="time">Chọn giờ:</label>
                                    <div class="time-slots">
                                        <?php $__currentLoopData = $time_slots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time_slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $is_booked = in_array($time_slot->time_slot, $booked_times); // Kiểm tra nếu giờ đã được đặt
                                            ?>
                                            <button type="button" class="btn-time <?php echo e($is_booked ? 'booked' : ''); ?>" 
                                                    onclick="toggleTimeSlot(this)" 
                                                    data-time="<?php echo e($time_slot->time_slot); ?>" 
                                                    data-price="<?php echo e($time_slot->price); ?>" 
                                                    <?php echo e($is_booked ? 'disabled' : ''); ?>>
                                                <?php echo e($time_slot->time_slot); ?>

                                            </button>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>                                    

                                </div>
                            
                                <div class="col col-half form-order-right">
                                    <input type="hidden" name="user_id" value="<?php echo e($userId); ?>">
                                    <input type="hidden" name="san_id" value="<?php echo e($sanId); ?>">
                                    <input type="hidden" name="total_price" id="total_price_input" value="0"> 
                                    <input type="hidden" name="selected_times" id="selected_times">
                                    <input type="hidden" name="images[]" value="path_to_image.jpg"> 
                                    <label for="name">Họ và tên:</label>
                                    <input type="text" id="name" name="name" required>
                                    <label for="phone">Số điện thoại:</label>
                                    <input type="text" id="phone" name="phone" required>
                                    <label>Giá tiền: <span id="total_price">0</span></label>
                                    <label for="notes">Ghi chú:</label>
                                    <textarea id="notes" name="notes" rows="4"></textarea>
                                </div>
                                <input type="submit" value="Đặt sân" class="order-button">
                            </form>     
                            
                            <script src="<?php echo e(asset('js/datsan.js')); ?>"></script>
                        </div>
                    </div>
                </div>

            </div>
            <div class="clear"></div>
        </div>
        <!-- End: Content -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/pick.blade.php ENDPATH**/ ?>