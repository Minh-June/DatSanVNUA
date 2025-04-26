

<?php $__env->startSection('title', 'Trang chủ'); ?>

<?php $__env->startSection('content'); ?>
        <div id="slider">
            <img src="<?php echo e(asset('./image/slider/slider1.jpg')); ?>" alt="Slider Image" style="width: 100%; height: auto;">
        </div>
        
        <!-- Begin: Content -->
        <?php $__currentLoopData = $groupedSans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tensan => $sans): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="content" class="content-section">
                <h2 class="content-heading">
                    <?php echo e($tensan); ?>

                </h2>

                <div class="content-list">
                    <?php $__currentLoopData = $sans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $san): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="content-item">
                            <?php
                                // Lấy hình ảnh từ mảng $images theo san_id
                                $imageData = $san->images->first(); // Lấy hình ảnh đầu tiên nếu có
                                if ($imageData) {
                                    // Hiển thị hình ảnh dưới dạng base64
                                    echo '<img src="' . $imageData->url . '" alt="" class="football-img">'; // Sử dụng phương thức getUrlAttribute
                                } else {
                                    // Hiển thị hình ảnh mặc định nếu không có hình ảnh
                                    echo '<img src="' . asset('./image/football.jpg') . '" alt="" class="football-img">';
                                }
                            ?>
                            <div class="content-body">
                                <h3 class="content-body-name">
                                    <?php echo e($san->tensan); ?> - <?php echo e($san->sosan); ?>

                                </h3>
                                <a class="order-football-btn" href="<?php echo e(route('dat-san', ['id' => $san->san_id])); ?>">Chọn sân</a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="clear"></div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <!-- End: Content -->


        <!-- Begin: Contact section -->
        <div id="contact" class="content-section">
            <h2 class="content-heading">LIÊN HỆ</h2>

            <div class="row contact-content">
                <div class="col col-half contact-infor">
                    <p><i class="fa-solid fa-location-dot"></i>Hà Nội, Việt Nam</p>
                    <p><i class="fa-solid fa-phone"></i>Điện thoại: <a href="tel:+00 151515">+84 356645445</a></p>
                    <p><i class="fa-solid fa-envelope"></i>Email: <a href="mailto:mail@mail.com">minhjune18@gmail.com</a></p>
                </div>

                <div class="col col-half contact-form">
                    <form action="">
                        <div class="row">
                            <div class="col col-half">
                                <input type="text" name="" placeholder="Tên" required id="" class="form-control">
                            </div>
                            <div class="col col-half s-mt-8">
                                <input type="email" name="" placeholder="Email" required id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-8">
                            <div class="col col-full">
                                <input type="text" name="" placeholder="Ghi chú" required id="" class="form-control">
                            </div>
                        </div>
                        <input class="contact-btn pull-right mt-16" type="submit" value="Gửi">
                    </form>

                </div>
                
            </div>
        </div>
        <!-- End: Contact section -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/home.blade.php ENDPATH**/ ?>