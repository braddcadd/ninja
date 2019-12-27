<?php $__env->startSection('body'); ?>
<?php echo $body; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('emails.master_contact', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>