<?php
$configData = Helper::appClasses();
$customizerHidden = 'customizer-hide';
?>



<?php $__env->startSection('layoutContent'); ?>
<!-- Content -->
<?php echo $__env->yieldContent('content'); ?>
<!--/ Content -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts/commonMaster', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\saasres\resources\views/tenant/layouts/app.blade.php ENDPATH**/ ?>