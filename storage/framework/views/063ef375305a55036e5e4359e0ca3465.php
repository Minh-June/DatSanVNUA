

<?php $__env->startSection('title', 'Đặt sân'); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <script>
        alert('Đặt sân thành công!');
    </script>
<?php endif; ?>

<div id="content" class="order-section">
    <h2 class="order-heading"><?php echo e($type_name); ?> - <?php echo e($yard_name); ?></h2>

    <div class="order-content">
        <div class="order-section-left"> 
            <?php if($yard->images->count() > 0): ?>
                <button class="main-arrow left">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <div class="slider-track-wrapper">
                    <div class="slider-track">
                        <?php $__currentLoopData = $yard->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <img src="<?php echo e(asset(Storage::url($img->image))); ?>" 
                                alt="Sân <?php echo e($yard_name); ?>" 
                                class="football-img" 
                                onclick="showAllImages()">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <button class="main-arrow right">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

                <p class="image-count">1/<?php echo e($yard->images->count()); ?></p>
            <?php else: ?>
                <img src="<?php echo e(asset('image/football.jpg')); ?>" alt="Sân <?php echo e($yard_name); ?>" class="football-img">
            <?php endif; ?>
        </div>

        <div class="order-section-right">
            <div class="container">

                <form action="<?php echo e(route('luu-thong-tin-don-dat-san')); ?>" method="POST" id="orderForm" onsubmit="return confirmBooking(event)">
                    <?php echo csrf_field(); ?>

                    
                    <div class="form-order-days">
                        <label for="date">Chọn ngày:</label>
                        <input type="hidden" id="yard_id_input" value="<?php echo e($yard_id); ?>">
                        <input type="date" id="date" name="date" style="outline:none;"
                            value="<?php echo e(old('date', $selected_date ?? date('Y-m-d'))); ?>"
                            min="<?php echo e(date('Y-m-d')); ?>"
                            onchange="window.location.href='<?php echo e(route('dat-san', ['yard_id'=>$yard_id])); ?>?date='+this.value">
                    </div>

                    
                    <div class="form-order-times">
                        <label for="time">Chọn giờ:</label>
                        <div class="time-slots" id="time_slots_container">
                            <?php $__currentLoopData = $times; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $range = $slot->range;
                                    $price = $slot->getPriceByDate($selected_date);
                                    $isAdminBooked   = in_array($range, $adminBookedTimes);
                                    $isSessionBooked = in_array($range, $sessionBookedTimes);
                                    $startTime = strtotime($selected_date . ' ' . explode(' - ', $range)[0]);
                                    $isPast = $startTime < time();
                                    $disabled = $isAdminBooked || $isSessionBooked || $isPast;
                                    $btnClass = ($isAdminBooked || $isSessionBooked) ? 'booked' : '';
                                ?>
                                <div class="time-slot-wrapper">
                                    <button type="button"
                                        class="btn-time <?php echo e($btnClass); ?>"
                                        data-time="<?php echo e($range); ?>"
                                        data-original-time="<?php echo e($range); ?>"
                                        data-price-weekday="<?php echo e($slot->price_weekday); ?>"
                                        data-price-weekend="<?php echo e($slot->price_weekend); ?>"
                                        data-is-classic="<?php echo e($slot->is_classic); ?>"
                                        <?php echo e($disabled ? 'disabled' : ''); ?>>
                                        <?php echo e($range); ?>

                                    </button>
                                    <button type="button" class="btn-plus hidden">+</button>
                                    <div class="extra-options hidden">
                                        <button type="button" class="btn-extra" data-extra="30" data-base="<?php echo e($range); ?>">30 phút</button>
                                        <button type="button" class="btn-extra" data-extra="60" data-base="<?php echo e($range); ?>">60 phút</button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <div id="selected_times"></div>

                    
                    <div id="suggested_times_wrapper" style="display:none; margin: 10px 0 20px 0;">
                        <label style="width:96px;"></label>
                        <div id="suggested_times"></div>
                    </div>

                    
                    <label style="display: inline-block; margin-bottom: 12px;">Thành tiền:
                        <span id="total_price" style="margin-left: 4px;">0đ</span>
                    </label><br>

                    
                    <label style="display: inline-block; margin-bottom: 12px;">Thông tin:</label>
                    <span id="selected_times_info"
                        style="font-weight: bold; color: var(--primary-color); margin-left: 13px; font-size: 19px;">
                        Bạn chưa chọn khung giờ nào
                    </span><br>

                    
                    <label for="notes">Ghi chú:</label>
                    <textarea id="notes" name="notes" rows="3" style="outline:none;"><?php echo e(old('notes')); ?></textarea>

                    
                    <input type="hidden" name="user_id" value="<?php echo e($userId); ?>">
                    <input type="hidden" name="yard_id" value="<?php echo e($yard_id); ?>">
                    <input type="hidden" name="type_id" value="<?php echo e($type_id); ?>">
                    <input type="hidden" name="type_name" value="<?php echo e($type_name); ?>">
                    <input type="hidden" name="total_price" id="total_price_input" value="0">
                    <input type="hidden" name="price_per_slot" id="price_per_slot_input" value="[]">
                    <input type="hidden" name="is_classic_per_slot" id="is_classic_per_slot_input" value="[]">
                    <input type="hidden" name="continue_booking" id="continue_booking">
                    <input type="hidden" name="name" value="<?php echo e($user->fullname ?? ''); ?>">
                    <input type="hidden" name="phone" value="<?php echo e($user->phonenb ?? ''); ?>">

                    <button type="submit" class="order-football-btn">Đặt sân</button>
                </form>

                <script>
                const sessionBookedTimes = <?php echo json_encode($sessionBookedTimes, 15, 512) ?>;
                const adminBookedTimes = <?php echo json_encode($adminBookedTimes, 15, 512) ?>;
                </script>
                <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script src="<?php echo e(asset('js/datsan.js')); ?>"></script>

            </div>
        </div>
    </div>

    <!-- Thông tin sân và Đăng ký thuê theo tháng -->
    <div class="order-infor">
        <div class="order-time-infor">
            <h2 class="order-heading">Bảng giá dịch vụ</h2>

            <table id="ListCustomers">
                <thead>
                    <tr>
                        <th>Khung giờ</th>
                        <th>Thứ 2 – Thứ 6</th>
                        <th>Thứ 7 & CN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $timesForUI; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $time): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            
                            <td>
                                <?php echo e(\Carbon\Carbon::parse($time->start)->format('H:i')); ?>

                                -
                                <?php echo e(\Carbon\Carbon::parse($time->end)->format('H:i')); ?>

                            </td>

                            
                            <td>
                                <?php if($time->price_weekday && $time->price_weekday > 0): ?>
                                    <?php echo e(number_format($time->price_weekday, 0, ',', '.')); ?>đ
                                <?php else: ?>
                                    <span style="color:#888;">Không cho thuê</span>
                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <?php if($time->price_weekend && $time->price_weekend > 0): ?>
                                    <?php echo e(number_format($time->price_weekend, 0, ',', '.')); ?>đ
                                <?php else: ?>
                                    <span style="color:#888;">Không cho thuê</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" style="text-align:center; padding:10px;">
                                Chưa có khung giờ nào cho sân này.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="month-rent-content">
            <h2 class="order-heading">Đăng ký thuê cố định</h2>

            <form method="POST" action="<?php echo e(route('luu-thue-theo-thang')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="yard_id" value="<?php echo e($yard_id); ?>">
                <input type="hidden" name="user_id" value="<?php echo e($userId); ?>">

                <div class="form-group">
                    <label for="from_date">Ngày bắt đầu:</label>
                    <input type="date"
                        name="from_date"
                        id="from_date"
                        value="<?php echo e(old('from_date', date('Y-m-d'))); ?>"
                        min="<?php echo e(date('Y-m-d')); ?>">
                </div>

                <div class="form-group">
                    <label for="to_date">Ngày kết thúc:</label>
                    <input type="date"
                        name="to_date"
                        id="to_date"
                        value="<?php echo e(old('to_date', date('Y-m-d', strtotime('+1 month')))); ?>"
                        min="<?php echo e(date('Y-m-d')); ?>">
                </div>

                <div class="form-group">
                    <label>Ngày trong tuần:</label>
                    <div class="weekday-list">
                        <?php $days = ['Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','Chủ nhật']; ?>
                        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="weekday-item" data-value="<?php echo e($index); ?>" onclick="toggleWeekday(this)">
                                <?php echo e($day); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <input type="hidden" name="weekdays" id="selectedWeekdays" value="">
                </div>

                <div class="form-group">
                    <label>Nhập giờ tự do:</label>
                    <div class="free-time-group">
                        <span>Từ</span>
                        <input type="time"
                            id="time_from"
                            name="time_from"
                            value="<?php echo e(old('time_from', '06:00')); ?>"
                            min="06:00"
                            max="22:30">

                        <span>Đến</span>
                        <input type="time"
                            id="time_to"
                            name="time_to"
                            value="<?php echo e(old('time_to', '22:00')); ?>"
                            min="06:30"
                            max="22:30">
                    </div>
                </div>

                <button type="submit" class="monthly-submit-btn">
                    Đăng ký thuê
                </button>
            </form>
        </div>
    </div>

    <!-- Sản phẩm liên quan -->
    <h2 class="order-heading" style="margin:15px 0 -10px 0;">Sản phẩm liên quan</h2>
    
    <?php if($similarProducts && $similarProducts->count() > 0): ?>
        <div class="store-list-container" style="min-height: 550px; margin:40px 40px 50px 40px;">
            <div class="store-card">
                <!-- Header -->
                <div class="store-header" onclick="window.location='<?php echo e(route('chi-tiet-cua-hang', $store->store_id)); ?>'">
                    <div class="store-avatar">
                        <?php if($store->user && $store->user->image): ?>
                            <img src="<?php echo e(asset('storage/' . $store->user->image)); ?>" alt="<?php echo e($store->name); ?>">
                        <?php else: ?>
                            <img src="<?php echo e(asset('images/default-avatar.png')); ?>" alt="Không có ảnh">
                        <?php endif; ?>
                    </div>
                    <div class="store-info">
                        <h3 class="store-name"><?php echo e($store->name); ?></h3>
                        <p class="store-owner"><?php echo e($store->user ? $store->user->fullname : 'Chưa xác định'); ?></p>
                    </div>
                </div>

                <!-- Gallery sản phẩm  -->
                <div class="store-products-gallery">
                    <?php if($similarProducts && $similarProducts->count() > 0): ?>
                        <?php $__currentLoopData = $similarProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                // Lấy size có giá thấp nhất nếu có
                                $defaultSize = $product->sizes->sortBy('price')->first();
                                $displayPrice = $defaultSize->price ?? $product->price;
                                $img = $product->images->first()->image ?? '';
                                $sizeName = $defaultSize->name ?? '';
                                $sizeId = $defaultSize->product_size_id ?? null;
                            ?>

                            <div class="product-item" onclick="window.location='<?php echo e(route('chi-tiet-san-pham', $product->product_id)); ?>'">
                                <img src="<?php echo e($img ? asset('storage/' . $img) : asset('images/no-image.png')); ?>" alt="<?php echo e($product->name); ?>">
                                <div class="product-info">
                                    <p class="product-name"><?php echo e($product->name); ?></p>
                                    <p class="product-price"><?php echo e(number_format($displayPrice, 0, ',', '.')); ?>đ</p>

                                    <form method="POST" action="<?php echo e(route('luu-san-pham-storesboard')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="product_id" value="<?php echo e($product->product_id); ?>">
                                        <input type="hidden" name="store_id" value="<?php echo e($product->store_id); ?>">
                                        <input type="hidden" name="name" value="<?php echo e($product->name); ?>">
                                        <input type="hidden" name="price" value="<?php echo e($displayPrice); ?>">
                                        <input type="hidden" name="image" value="<?php echo e($img); ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="size" value="<?php echo e($sizeName); ?>">
                                        <input type="hidden" name="product_size_id" value="<?php echo e($sizeId); ?>">
                                        <button type="submit" class="store-add-btn">Thêm vào giỏ hàng</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="no-products">Chưa có sản phẩm</p>
                    <?php endif; ?>
                </div>
                <a href="<?php echo e(route('chi-tiet-cua-hang', $store->store_id)); ?>" class="store-action-btn">Đi đến cửa hàng</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Hiển thị tất cả ảnh sân -->
    <div id="multi-image-popup" onclick="hideAllImages()" style="
        display: none;
        position: fixed;
        z-index: 2;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.8);
        justify-content: center;
        align-items: center;
    ">
        <div id="popupImages" onclick="event.stopPropagation()" style="
            display: flex; 
            gap: 15px;
            max-width: 90%;
            overflow-x: auto;
        ">
            <?php $__currentLoopData = $yard->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <img src="<?php echo e(asset(Storage::url($img->image))); ?>"
                    alt="Ảnh sân"
                    class="popup-img"
                    style="max-height: 600px; max-width: 500px; box-shadow: 0 0 10px #000; opacity:0; transition: opacity 0.5s ease;">
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    
</div>

<script>
    // Chọn nhiều ngày bằng span thay checkbox
    function toggleWeekday(el) {
        el.classList.toggle('selected');

        // Lấy tất cả các weekday được chọn
        const selected = Array.from(document.querySelectorAll('.weekday-item.selected'))
                            .map(e => e.dataset.value);

        // Gán vào input hidden
        document.getElementById('selectedWeekdays').value = JSON.stringify(selected);
    }

    // Ẩn hiện ảnh sân to
    function showAllImages() {
        const popup = document.getElementById('multi-image-popup');
        popup.style.display = 'flex';

        // fade in từng ảnh
        const imgs = document.querySelectorAll('#popupImages .popup-img');
        imgs.forEach((img, idx) => {
            setTimeout(() => {
                img.style.opacity = 1;
            }, idx * 100); // cách nhau 100ms
        });
    }

    function hideAllImages() {
        const popup = document.getElementById('multi-image-popup');
        
        // fade out từng ảnh trước khi ẩn popup
        const imgs = document.querySelectorAll('#popupImages .popup-img');
        imgs.forEach((img) => img.style.opacity = 0);

        setTimeout(() => {
            popup.style.display = 'none';
        }, 300); // thời gian fade out
    }

    // Trượt slides
    let images = <?php echo json_encode($yard->images->pluck('image'), 15, 512) ?>;
    let currentIndex = 0;
    const track = document.querySelector('.order-section-left .slider-track');
    const totalSlides = images.length;
    const countEl = document.querySelector('.order-section-left .image-count');

    function changeMainImage(direction) {
        if (!images || images.length === 0) return;

        currentIndex += direction;

        // vòng lặp
        if (currentIndex < 0) currentIndex = totalSlides - 1;
        if (currentIndex >= totalSlides) currentIndex = 0;

        track.style.transform = `translateX(-${currentIndex * 100}%)`;

        if (countEl) countEl.textContent = `${currentIndex + 1}/${totalSlides}`;
    }

    // Auto slide
    let autoSlide = setInterval(() => changeMainImage(1), 3000);

    // Thêm sự kiện cho nút
    const btnLeft = document.querySelector('.order-section-left .main-arrow.left');
    const btnRight = document.querySelector('.order-section-left .main-arrow.right');

    if (btnLeft && btnRight) {
        btnLeft.addEventListener('click', () => {
            clearInterval(autoSlide);
            changeMainImage(-1);
        });
        btnRight.addEventListener('click', () => {
            clearInterval(autoSlide);
            changeMainImage(1);
        });
    }

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/pick.blade.php ENDPATH**/ ?>