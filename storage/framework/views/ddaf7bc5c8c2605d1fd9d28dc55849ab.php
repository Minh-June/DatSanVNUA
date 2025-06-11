

<?php $__env->startSection('title', 'Danh sĂ¡ch ngÆ°á»i dĂ¹ng'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hiá»ƒn thá»‹ thĂ´ng bĂ¡o -->
    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <script>
            alert("<?php echo e($errors->first('keyword')); ?>");
        </script>
    <?php endif; ?>


    <h3><?php echo e(isset($xem_user) ? 'ThĂ´ng tin ngÆ°á»i dĂ¹ng' : 'Danh sĂ¡ch ngÆ°á»i dĂ¹ng'); ?></h3>

    <!-- Thanh top-bar luĂ´n hiá»ƒn thá»‹ -->
    <div class="admin-top-bar">
        <div class="admin-search">
            <?php if(!isset($xem_user)): ?>
            <form method="GET" action="<?php echo e(route('quan-ly-nguoi-dung')); ?>">
                <label for="type_id">TĂ¬m ngÆ°á»i dĂ¹ng:</label>
                <input type="text" name="keyword" placeholder="Nháº­p thĂ´ng tin cáº§n tĂ¬m" value="<?php echo e(request('keyword')); ?>" required pattern="^[\p{L}\s]+$" title="Chá»‰ nháº­p chá»¯ cĂ¡i vĂ  khoáº£ng tráº¯ng">
                <button class="admin-search-btn" type="submit">TĂ¬m kiáº¿m</button>
            </form>
            <?php endif; ?>
        </div>

        <div class="admin-add-btn">
            <?php if(isset($xem_user)): ?>
                <a href="<?php echo e(route('quan-ly-nguoi-dung')); ?>">Quay láº¡i danh sĂ¡ch</a>
            <?php else: ?>
                <a href="<?php echo e(route('dang-ky')); ?>">ThĂªm ngÆ°á»i dĂ¹ng má»›i</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if(isset($xem_user)): ?>
        <!-- Hiá»ƒn thá»‹ thĂ´ng tin ngÆ°á»i dĂ¹ng -->
        <div class="adminedit">
            <form>
                <?php echo csrf_field(); ?>
                <label for="fullname">Há» vĂ  tĂªn:</label>
                <input type="text" name="fullname" value="<?php echo e($xem_user->fullname); ?>" disabled><br>

                <label for="gender">Giá»›i tĂ­nh:</label>
                <input type="text" name="gender" value="<?php echo e($xem_user->gender); ?>" disabled><br>

                <label for="birthdate">NgĂ y sinh:</label>
                <input type="date" name="birthdate" value="<?php echo e($xem_user->birthdate); ?>" disabled><br>

                <label for="phonenb">Sá»‘ Ä‘iá»‡n thoáº¡i:</label>
                <input type="text" name="phonenb" value="<?php echo e($xem_user->phonenb); ?>" disabled><br>

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo e($xem_user->email); ?>" disabled><br>
            </form>                          
        </div>
        <br>
    <?php else: ?>
        <!-- Hiá»ƒn thá»‹ báº£ng dá»¯ liá»‡u -->
        <table id='ListCustomers'>
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Há» vĂ  tĂªn</th>
                    <th>TĂªn tĂ i khoáº£n</th>
                    <th>Vai trĂ²</th>
                    <th>ThĂ´ng tin</th>
                    <th colspan="2">TĂ¹y chá»n</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td><?php echo e($user->fullname); ?></td>
                    <td><?php echo e($user->username); ?></td>
                    <td><?php echo e($user->role == 0 ? 'Admin' : 'KhĂ¡ch hĂ ng'); ?></td>
                    <td>
                        <a href="<?php echo e(route('quan-ly-nguoi-dung', ['xem' => $user->user_id])); ?>">Xem chi tiáº¿t</a>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('cap-nhat-vai-tro-nguoi-dung', $user->user_id)); ?>">
                            <?php echo csrf_field(); ?>
                            <select name="role">
                                <option value="0" <?php echo e($user->role == 0 ? 'selected' : ''); ?>>Admin</option>
                                <option value="1" <?php echo e($user->role == 1 ? 'selected' : ''); ?>>KhĂ¡ch hĂ ng</option>
                            </select><br>
                            <button type="submit" class="update-btn">Cáº­p nháº­t</button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="<?php echo e(route('xoa-nguoi-dung', ['user_id' => $user->user_id])); ?>" onsubmit="return confirm('Báº¡n cĂ³ cháº¯c cháº¯n muá»‘n xĂ³a ngÆ°á»i dĂ¹ng nĂ y khĂ´ng?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="update-btn">XĂ³a</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\laragon\www\qldatsan\resources\views/admin/users/index.blade.php ENDPATH**/ ?>
