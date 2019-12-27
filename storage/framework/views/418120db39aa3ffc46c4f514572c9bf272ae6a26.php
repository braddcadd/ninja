<?php $__env->startSection('markup'); ?>
    <?php if(!$invitationMessage): ?>
        <?php echo $__env->make('emails.confirm_action', ['user' => $user], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <h2><?php echo e(trans('texts.confirmation_header')); ?></h2>
    <div>
        <?php echo e($invitationMessage . trans('texts.button_confirmation_message')); ?>

    </div>
    &nbsp;
    <div>
        <center>
            <?php echo $__env->make('partials.email_button', [
                'link' => URL::to("user/confirm/{$user->confirmation_code}"),
                'field' => 'confirm',
                'color' => '#36c157',
            ], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </center>
    </div>
    &nbsp;
    <div>
        <?php echo e(trans('texts.email_signature')); ?><br/>
        <?php echo e(trans('texts.email_from')); ?>

    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('emails.master_user', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>