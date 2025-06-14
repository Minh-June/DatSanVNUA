

<?php $__env->startSection('title', 'Đặt sân'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>
        alert('Đặt sân thành công!');
    </script>
<?php endif; ?>

<div id="content" class="order-section">
    <h2 class="order-heading"><?php echo e($yard_name); ?></h2>
    <div class="order-content">
        <div class="order-section-left">
            <?php if($yard_image): ?>
                <img src="<?php echo e(asset(Storage::url($yard_image->image))); ?>" alt="Sân <?php echo e($yard_name); ?>" class="football-img" style="cursor: pointer;" onclick="showAllImages()">
            <?php else: ?>
                <img src="<?php echo e(asset('image/football.jpg')); ?>" alt="Sân <?php echo e($yard_name); ?>" class="football-img">
            <?php endif; ?>
        </div>

        <div class="order-section-right">
            <div class="container">
                <p>* Lưu ý: Nếu bạn muốn đặt sân ngoài khung giờ có sẵn, vui lòng liên hệ chủ sân qua SĐT: 0356645445</p>
                <form action="<?php echo e(route('luu-thong-tin-don-dat-san')); ?>" method="POST" id="orderForm" onsubmit="return confirmBooking(event)">
                    <?php echo csrf_field(); ?>
                    <div class="form-order-days">
                        <label for="date">Chọn ngày:</label>
                        <input type="hidden" id="yard_id_input" value="<?php echo e($yard_id); ?>">
                        <input type="date" id="date" name="date"
                            value="<?php echo e(old('date', $selected_date ?? date('Y-m-d'))); ?>"
                            min="<?php echo e(date('Y-m-d')); ?>"
                            onchange="onDateChange()">
                    </div>

                    <div class="form-order-times">
                        <label for="time">Chọn giờ:</label>
                        <div class="time-slots" id="time_slots_container">
                            <?php $__currentLoopData = $times; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $isAdminBooked = in_array($slot->time, $adminBookedTimes);
                                    $isSessionBooked = in_array($slot->time, $sessionBookedTimes);
                                    $disabled = $isAdminBooked || $isSessionBooked;
                                ?>
                                <button type="button" class="btn-time <?php echo e($disabled ? 'booked' : ''); ?>"
                                        data-time="<?php echo e($slot->time); ?>"
                                        data-price="<?php echo e($slot->price); ?>"
                                        <?php echo e($disabled ? 'disabled' : ''); ?>>
                                    <?php echo e($slot->time); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <input type="hidden" name="user_id" value="<?php echo e($userId); ?>">
                    <input type="hidden" name="yard_id" value="<?php echo e($yard_id); ?>">
                    <input type="hidden" name="total_price" id="total_price_input" value="0">
                    <div id="selected_times"></div>
                    <input type="hidden" name="continue_booking" id="continue_booking">
                    <input type="hidden" name="name" value="<?php echo e($user->fullname ?? ''); ?>">
                    <input type="hidden" name="phone" value="<?php echo e($user->phonenb ?? ''); ?>">

                    <label>Thành tiền: <span id="total_price">0 VNĐ</span></label><br><br>
                    <label for="notes">Ghi chú:</label>
                    <textarea id="notes" name="notes" rows="3"><?php echo e(old('notes')); ?></textarea>
                    <button type="submit" class="order-football-btn">Đặt sân</button>
                </form>

                <script src="<?php echo e(asset('js/datsan.js')); ?>"></script>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>

<!-- Lightbox hiển thị tất cả ảnh sân -->
    <div id="multi-image-popup" onclick="hideAllImages()" style="
        display: none;
        position: fixed;
        z-index: 2;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.8);
        justify-content: center;
        align-items: center;
    ">
        <div onclick="event.stopPropagation()" style="display: flex; gap: 15px;">
            <?php $__currentLoopData = $yard->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <img src="<?php echo e(asset(Storage::url($img->image))); ?>"
                    alt="Ảnh sân"
                    style="max-height: 700px; max-width: 525px; box-shadow: 0 0 10px #000;">
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <script>
        function showAllImages() {
            document.getElementById('multi-image-popup').style.display = 'flex';
        }

        function hideAllImages() {
            document.getElementById('multi-image-popup').style.display = 'none';
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/pick.blade.php ENDPATH**/ ?>