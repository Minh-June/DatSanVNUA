

<?php $__env->startSection('title', 'Trang chá»§'); ?>

<?php $__env->startSection('content'); ?>
        <div id="slider">
            <img src="<?php echo e(asset('image/slider/slider1.jpg')); ?>" alt="Slider Image" style="width: 100%; height: auto;">
        </div>
        
        <!-- Begin: Content -->
        <?php $__currentLoopData = $groupedYards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $typeName => $yards): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div id="content" class="content-section">
                <h2 class="content-heading">
                    <?php echo e($typeName); ?>

                </h2>
                <div class="content-list">
                    <?php $__currentLoopData = $yards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $yard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="content-item">
                            <img src="<?php echo e($yard->first_image_url ?? asset('image/football.jpg')); ?>" alt="" class="football-img">
                            <div class="content-body">
                                <h3 class="content-body-name">
                                    <?php echo e($yard->name); ?>

                                </h3>
                                <a href="<?php echo e(route('dat-san', ['yard_id' => $yard->yard_id])); ?>" class="order-football-btn">Chá»n sĂ¢n</a>
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
            <h2 class="content-heading">LIĂN Há»†</h2>

            <div class="row contact-content">
                <div class="col col-half contact-infor">
                    <div class="contact-infor-header">  
                        <img src="/image/logo.png" alt="Logo máº·c Ä‘á»‹nh">
                        <div class="contact-infor-text">  
                            <h3>Há»ŒC VIá»†N NĂ”NG NGHIá»†P VIá»†T NAM</h3>
                            <h5>VIETNAM NATIONAL UNIVERSITY OF AGRICULTURE</h5>
                        </div>
                    </div>
                    <p><i class="fa-solid fa-location-dot"></i>TrĂ¢u Quá»³, Gia LĂ¢m, HĂ  Ná»™i, Viá»‡t Nam</p>
                    <p><i class="fa-solid fa-phone"></i>Äiá»‡n thoáº¡i: 84.024.62617586</p>
                    <p><i class="fa-solid fa-envelope"></i>Email: webmaster@vnua.edu.vn</p>
                </div>

                <div class="col col-half contact-form">
                    <form action="">
                        <div class="row">
                            <div class="col col-half">
                                <input type="text" name="" placeholder="TĂªn" required id="" class="form-control">
                            </div>
                            <div class="col col-half s-mt-8">
                                <input type="email" name="" placeholder="Email" required id="" class="form-control">
                            </div>
                        </div>
                        <div class="row mt-8">
                            <div class="col col-full">
                                <input type="text" name="" placeholder="Ghi chĂº" required id="" class="form-control">
                            </div>
                        </div>
                        <input class="contact-btn pull-right mt-16" type="submit" value="Gá»­i">
                    </form>

                </div>
                
            </div>
        </div>
        <!-- End: Contact section -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Workspace\laragon\www\qldatsan\resources\views/client/home.blade.php ENDPATH**/ ?>