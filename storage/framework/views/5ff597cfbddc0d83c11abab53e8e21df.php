
<?php $__env->startSection('content'); ?>
    <iframe
            width="100%"
            style="height: calc(100vh - 30px)"
            src="<?php echo e(route(config('chatify.path'),['id'=>request('user_id')])); ?>"
            frameborder="0"
    ></iframe>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Layout::user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/riyaoeiu/public_html/themes/Base/User/Views/frontend/chat/index.blade.php ENDPATH**/ ?>